    <table class="table table-hover table-striped">
        <tbody>
            <tr>
                <th width="80">日志ID</th>
                <td>{$model.id}</td>
            </tr>
            <tr>
                <th>管理员</th>
                <td>{$manager.username}</td>
            </tr>
            <tr>
                <th>操作</th>
                <td>{$model.action}</td>
            </tr>
            <tr>
                <th>结果</th>
                <td>{$model.result}</td>
            </tr>
            <tr>
                <th>日期</th>
                <td>{$model.create_time|showdate}</td>
            </tr>
            <tr>
                <th>IP</th>
                <td>{$model.ip}</td>
            </tr>
            <tr>
                <th>备注</th>
                <td>{$model.remark}</td>
            </tr>
        </tbody>
    </table>