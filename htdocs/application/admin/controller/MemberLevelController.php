<?php
/**
 * Created by IntelliJ IDEA.
 * User: shirne
 * Date: 2018/4/11
 * Time: 9:03
 */

namespace app\admin\controller;
use app\admin\model\MemberLevelModel;
use app\admin\validate\MemberLevelValidate;
use think\Db;

/**
 * 会员组管理
 */
class MemberLevelController extends BaseController
{
    /**
     * 会员级别列表
     */
    public function index()
    {
        $model = Db::name('memberLevel');

        $lists=$model->select();
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    /**
     * 添加等级
     */
    public function add()
    {
        if ($this->request->isPost()) {
            //如果用户提交数据
            $data=$this->request->post();
            $validate=new MemberLevelValidate();
            $validate->setId();
            if (!$validate->check($data)) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($validate->getError());
                exit();
            } else {
                $levelModel=MemberLevelModel::create($data);
                $insertId=$levelModel->getLastInsID();
                if ($insertId!==false) {
                    cache('levels', null);
                    user_log($this->mid,'addlevel',1,'添加会员组'.$insertId ,'manager');
                    $this->success("添加成功", url('memberLevel/index'));
                } else {
                    $this->error("添加失败");
                }
            }
        }
        $this->assign('model',array(
            'commission_layer'=>3
        ));
        $this->assign('styles',getTextStyles());
        return $this->fetch('update');
    }
    /**
     * 更新会员组
     */
    public function update($id)
    {
        $id = intval($id);
        if ($this->request->isPost()) {
            $data=$this->request->post();
            $validate=new MemberLevelValidate();
            $validate->setId($id);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }else{
                $model=MemberLevelModel::get($id);
                if ($model->allowField(true)->save($data)) {
                    cache('levels', null);
                    user_log($this->mid,'updatelevel',1,'修改会员组'.$id ,'manager');
                    $this->success("更新成功", url('memberLevel/index'));
                } else {
                    $this->error("更新失败");
                }
            }
        }
        $model = MemberLevelModel::get($id);
        $this->assign('model',$model);
        $this->assign('styles',getTextStyles());
        return $this->fetch();
    }
    /**
     * 删除会员组
     */
    public function delete($id)
    {
        $id = intval($id);
        $count=Db::name('Member')->where('level_id',$id)->count();
        if($count>0){
            $this->error("该分组尚有会员,不能删除");
        }
        $model = Db::name('memberLevel');
        $result = $model->delete($id);
        if($result){
            cache('levels', null);
            $this->success("删除成功", url('memberLevel/index'));
        }else{
            $this->error("删除失败");
        }
    }
}