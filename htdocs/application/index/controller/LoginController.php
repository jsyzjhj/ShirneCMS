<?php

namespace app\index\controller;
use app\common\validate\MemberValidate;
use sdk\WechatAuth;
use think\Db;

/**
 * 用户本地登陆和第三方登陆
 */
class LoginController extends BaseController{

    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    protected $token;
    protected $appid;
    protected $appsecret;
    protected $encodingaeskey;
    protected $options;


    public function initialize(){
        parent::initialize();
        $this->token = $this->config["token"];
        $this->appid = $this->config["appid"];
        $this->appsecret = $this->config["appsecret"];
        $this->encodingaeskey = $this->config["encodingaeskey"];
        //配置
        $this->options = array(
            'token'=>$this->token,
            'encodingaeskey'=>$this->encodingaeskey,
            'appid'=>$this->appid,
            'appsecret'=>$this->appsecret
        );
    }




    public function index()
    {
        $this->login();
    }

    public function login($type=null)
    {
        if($this->userid){
            $this->success('您已登录',url('index/index'));
            exit;
        }
        //方式1：本地账号登陆
        if(empty($type)){
            if(!$this->request->isPost()){
              $this->display('login');
            }
            if($this->request->isPost()){
                $code = $this->request->post('verify','','strtolower');
                //验证验证码是否正确
                if(!($this->check_verify($code))){
                    $this->error('验证码错误');
                }

                $data['username'] = $this->request->post('username');
                $password = $this->request->post('password');
                $member = Db::name('member')->where($data)->find();
                if(!empty($member) && $member['password']==encode_password($password,$member['salt'])){

                    if($member['status']==0){
                        user_log($member['id'], 'login', 0, '账号已禁用' );
                        $this->error("您的账号已禁用");
                    }else {
                        setLogin($member);
                        $redirect=redirect()->restore();
                        if(empty($redirect->getData())){
                            $url=url('member/index');
                        }else{
                            $url=$redirect->getTargetUrl();
                        }

                        $this->success("登陆成功",$url);
                    }
                }else{
                    user_log($member['id'],'login',0,'密码错误:'.$password);
                    $this->error("账号或密码错误");
                }
            }
            return;
        }
        //方式2：如果是微信登录（微信内部浏览器登录，非扫码登录）
        if(strtolower($type) == "weixin"){
            $redirect = url('Login/wechatCallback','',true);
            $scope = "snsapi_userinfo";
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri=$redirect&response_type=code&scope=$scope&state=STATE#wechat_redirect";
            redirect($url);
        }

        //方式3：QQ  weibo  github第三方登陆 
        //验证允许实用的登陆方式，可在后台用代码实现
    
        $can_use = in_array(strtolower($type), array('qq','sina','github'));
        if(!$can_use){
            $this->error("不允许使用此方式登陆");
        }
        //验证通过  使用第三方登陆
        if($type != null){
            $sns = WechatAuth::getInstance($type);
            redirect($sns->getRequestCodeURL());  
        }
        
    }

    //QQ weibo  github登录回调地址
    public function callback($type = null, $code = null) 
    {
      
        if(empty($type) || empty($code)){
            $this->error('参数错误');  
        } 
     
        $sns = ThinkOauth::getInstance($type);

        //腾讯微博需传递的额外参数
        $extend = null;
        if ($type == 'tencent') {
            $extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
        }
        $tokenArray = $sns->getAccessToken($code, $extend);
        $openid = $tokenArray['openid'];
        //$token = $tokenArray['access_token'];  //根据需求储存  主要用来刷新并延长授权时间
        //
        //执行后续操作,代码自己实现。
        //请记住每个用户的openid都是唯一的,所以把openid存到数据库即可
        $member = D('MemberView');
        //根据openid判断用户是否存在，如果存在 ，判断用户是否被禁用。如果不存在,把openid存到数据库,相当于注册用户

        #
        #
        #  代码自己实现
        #
        #
        #
    
        
    }
    
    /**
     * 微信登陆回调地址
     * 如果需要手机微信注册 请用这个方法 
     * 参考文档：http://mp.weixin.qq.com/wiki/9/01f711493b5a02f24b04365ac5d8fd95.html
     */
    public function wechatCallback()
    {
        $data=array();
        $wechat = new WechatAuth($this->options);
        $wxdata = $wechat->getOauthAccessToken($this->request->get('code'));
        /**
          $wxdata 字段
         {
           "access_token":"ACCESS_TOKEN",
           "expires_in":7200,
           "refresh_token":"REFRESH_TOKEN",
           "openid":"OPENID",
           "scope":"SCOPE",
           "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
         }
        **/
        $openid = $wxdata['openid'];
        $access_token = $wxdata['access_token'];
        session('openid',$openid);
        session('access_token',$access_token);
        //获取AUTH用户资料
        $oauthUserinfo = $wechat->getOauthUserinfo($access_token,$openid);
        /**
        {
           "openid":" OPENID",
           "nickname": NICKNAME,
           "sex":"1",
           "province":"PROVINCE"
           "city":"CITY",
           "country":"COUNTRY",
            "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46", 
            "privilege":[
            "PRIVILEGE1"
            "PRIVILEGE2"
            ],
            "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
        }
        **/
        //是否关注微信号 1：关注  0：未关注  根据实际情况确定是不是要用
        //session('subscribe',$userInfo['subscribe']);
        //组合数据库中的用户字段
        $data['openid'] = $oauthUserinfo['openid'];
        $data['avatar'] =$oauthUserinfo['headimgurl'];
        $data['status'] = 1;
        $data['create_time'] = time();
        $data['update_time'] = time();

        #
        #
        #  判断用户是否存在和和注册用户的代码自己实现。
        #
        #
        #
        
    }

    public function getpassword(){
        $step=$this->request->get('step/d',1);
        $username=$this->request->post('username');
        $authtype=$this->request->post('authtype');

        if($step==2 || $step==3){
            $step--;
            if(empty($username))$this->error("请填写用户名");
            if(empty($authtype))$this->error("请选择认证方式");
            $user=Db::name('member')->where(array('username'=>$username))->find();
            if(empty($user)){
                $this->error("该用户不存在");
            }
            if(empty($user[$authtype.'_bind']))$this->error("认证方式无效");

            switch ($authtype){
                case 'email':
                    $this->assign('sendtoname',"邮箱");
                    break;
                case 'mobile':
                    $this->assign('sendtoname',"手机");
                    break;
            }
            $step++;
        }
        if($step==3){
            $step--;
            $sendto=$this->request->post('sendto');
            $code=$this->request->post('checkcode');
            $crow=Db::name('checkcode')->where(array('sendto'=>$sendto,'checkcode'=>$code,'is_check'=>0))->order('create_time DESC')->find();
            $time=time();
            if(!empty($crow) && $crow['create_time']>$time-60*5){
                Db::name('checkcode')->where(array('id' => $crow['id']))->save(array('is_check' => 1, 'check_at' => $time));
                session('passed',$username);
            }else{
                $this->error("验证码已失效");
            }


            $step++;
        }

        if($step==4){
            $step--;
            $passed=session('passed');
            if(empty($passed)){
                $this->error("非法操作");
            }
            $password=$this->request->post('password');
            $repassword=$this->request->post('repassword');

            if(empty($password))$this->error("请填写密码");
            if(strlen($password)<6 || strlen($password)>20)$this->error("密码长度 6-20");

            if($password != $repassword){
                $this->error("两次密码输入不一致");
            }
            $data['salt'] = random_str(8);
            $data['password'] = encode_password($password, $data['salt']);
            $data['update_at'] = time();
            if (Db::name('member')->where(array('username'=>$passed))->update($data)) {
                $this->success("密码设置成功",url('Login/index'));
            }
        }

        $this->assign('username',$username);
        $this->assign('authtype',$authtype);
        $this->assign('step',$step);
        $this->assign('nextstep',$step+1);
        $this->display();
    }
    public function checkusername(){
        $username=$this->request->post('username');
        if(empty($username))$this->error("请填写用户名");
        $user=Db::name('member')->where(array('username'=>$username))->find();
        if(empty($user)){
            $this->error("该用户不存在");
        }
        $types=array();
        if($user['email_bind'])$types[]='email';
        if($user['mobile_bind'])$types[]='mobile';
        if(empty($types)) {
            $this->error("您的帐户未绑定任何有效资料，请联系客服处理。");
        }else{
            $this->success('', '',$types);
        }
    }

    public function register($agent=''){
        $this->seo("会员注册");

        if(!empty($agent)){
            $amem=Db::name('Member')->where(array('isagent'=>1,'agentcode'=>$agent))->find();
            if(!empty($amem)){
                session('agent',$amem['id']);
            }
        }

        if($this->request->isPost()){
            $data=array();
            $data['username']=$this->request->post('username');
            $data['password']=$this->request->post('password');
            $data['email']=$this->request->post('email');
            $data['realname']=$this->request->post('realname');
            $data['mobile']=$this->request->post('mobile');

            $validate=new MemberValidate();
            $validate->setId();
            if(!$validate->scene('register')->check($data)){
                $this->error($validate->getError());
            }

            $invite_code=$this->request->post('invite_code');
            if(($this->config['m_invite']==1 && !empty($invite_code)) || $this->config['m_invite']==2) {
                if (empty($invite_code)) $this->error("请填写激活码");
                $invite = Db::name('invite_code')->where(array('code' => $invite_code, 'is_lock' => 0, 'member_use' => 0))->find();
                if (empty($invite) || ($invite['invalid_at'] > 0 && $invite['invalid_at'] < time())) {
                    $this->error("激活码不正确");
                }
            }

            Db::startTrans();
            if(!empty($invite)) {
                $invite = Db::name('invite_code')->lock(true)->find($invite['id']);
                if (!empty($invite['member_use'])) {
                    Db::rollback();
                    $this->error("激活码已被使用");
                }
            }
            $data['salt']=random_str(8);
            $data['password']=encode_password($data['password'],$data['salt']);
            $data['login_ip']=$this->request->ip();
            $data['referer']=empty($invite['member_id'])?session('agent'):$invite['member_id'];

            if($invite['level_id']){
                $data['level_id']=$invite['level_id'];
            }else{
                $data['level_id']=getDefaultLevel();
            }

            $userid=Db::name('member')->insert($data);

            if(empty($userid)){
                Db::rollback();
                $this->error("注册失败");
            }
            if(!empty($invite)) {
                $invite['member_use'] = $userid;
                $invite['use_at'] = time();
                Db::name('invite_code')->update($invite);
            }
            Db::commit();
            setLogin($data);
            $redirect=redirect()->restore();
            if(empty($redirect->getData())){
                $url=url('member/index');
            }else{
                $url=$redirect->getTargetUrl();
            }
            $this->success("注册成功",$url);
        }else{
            return $this->fetch();
        }
    }

    public function checkunique($type='username'){
        if(!in_array($type,array('username','email','mobile'))){
            $this->error('参数不合法');
        }
        $member=Db::name('member');
        $val=$this->request->get('value');
        $m=$member->where(array($type=>$val))->find();
        $json=array();
        $json['error']=0;
        if(!empty($m))$json['error']=1;
        echo json_encode($json);
        exit;
    }

    public function verify(){
        $Verify = new \think\captcha\Captcha(array('seKey'=>'foreign'));
        $Verify->codeSet = '0123456789';
        $Verify->fontSize = 13;
        $Verify->length = 4;
        $Verify->entry();
    }
    protected function check_verify($code){
        $verify = new \think\captcha\Captcha(array('seKey'=>'foreign'));
        return $verify->check($code);
    }

    public function logout()
    {
        clearLogin();
        $this->success("已成功退出登陆");

    }

    /**
     * 忘记密码
     */
    public function forgot()
    {
        return $this->fetch();
    }
}
