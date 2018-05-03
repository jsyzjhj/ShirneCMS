<extend name="public:base" />

<block name="body">

<include file="public/bread" menu="Board" section="主面板" title=""/>
<div id="page-wrapper">

    <div class="row">
        <div class="col-lg-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <i class="ion-chatbox-working ion-5x"></i>
                        </div>
                        <div class="col-6 text-right">
                            <p class="announcement-heading">{$stat.feedback}</p>
                            <p class="announcement-text">留言</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer announcement-bottom">
                    <nav class="nav nav-fill">
                        <a class="nav-item nav-link" href="{:url('feedback/index')}"><i class="ion-navicon"></i> 查看留言 </a>
                        <a class="nav-item nav-link" href="{:url('feedback/statics')}"><i class="ion-stats-bars"></i> 留言统计 </a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <i class="ion-person-stalker ion-5x"></i>
                        </div>
                        <div class="col-6 text-right">
                            <p class="announcement-heading">{$stat.member}</p>
                            <p class="announcement-text">会员</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer announcement-bottom">
                    <nav class="nav nav-fill">
                        <a class="nav-item nav-link" href="{:url('member/index')}"><i class="ion-navicon"></i> 管理会员 </a>
                        <a class="nav-item nav-link" href="{:url('member/statics')}"><i class="ion-stats-bars"></i> 会员统计 </a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <i class="ion-edit ion-5x"></i>
                        </div>
                        <div class="col-6 text-right">
                            <p class="announcement-heading">{$stat.article}</p>
                            <p class="announcement-text">文章</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer announcement-bottom">
                    <nav class="nav nav-fill">
                        <a class="nav-item nav-link" href="{:url('article/index')}"><i class="ion-navicon"></i> 管理文章 </a>
                        <a class="nav-item nav-link" href="{:url('article/add')}"><i class="ion-plus"></i> 发布文章 </a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <i class="ion-link ion-5x"></i>
                        </div>
                        <div class="col-6 text-right">
                            <p class="announcement-heading">{$stat.links}</p>
                            <p class="announcement-text">链接</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer announcement-bottom">
                    <nav class="nav nav-fill">
                        <a class="nav-item nav-link" href="{:url('links/index')}"><i class="ion-navicon"></i> 管理链接 </a>
                        <a class="nav-item nav-link" href="{:url('links/statics')}"><i class="ion-stats-bars"></i> 文章统计 </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-default">
                <div class="card-header">
                    <h5 class="panel-title">会员统计</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th width="80">总会员</th>
                            <td>{$mem.total}</td>
                        </tr>
                        <tr>
                            <th width="80">正常会员</th>
                            <td>{$mem.avail}</td>
                        </tr>
                        <tr>
                            <th width="80">总代理数</th>
                            <td>{$mem.agent}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-default">
                <div class="card-header">
                    <h5 class="panel-title">资金统计</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th width="80">总充值</th>
                            <td>{$money.total_charge|showmoney}</td>
                        </tr>
                        <tr>
                            <th width="80">总提现</th>
                            <td>{$money.total_cash|showmoney}</td>
                        </tr>
                        <tr>
                            <th width="80">总余额</th>
                            <td>{$money.total_money|showmoney}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

</block>