<?php
namespace app\manage\controller;

use think\Url;
use think\Request;
use think\Db;
use app\common\validate\manage\MemberValidate;
use app\common\model\manage\MemberModel;
use app\common\model\manage\MenuModel;
use app\common\model\manage\FileModel;
use app\common\model\manage\ActionLogModel;
use app\common\logic\manage\MemberLogic;
use app\common\logic\common\CommonLogic;

class Index extends Base
{

    /**
     * 控制台
     */
    public function index(Request $request)
    {
        $this->site_title = '控制台';
        
        // 基础统计
        $site_info = [
            'member_num' => MemberModel::count(),
            'menu_num' => MenuModel::count(),
            'file_num' => FileModel::count(),
            'log_num' => ActionLogModel::count()
        ];
        $this->assign('site_info', $site_info);
        
        // 系统信息
        $mysql_version = Db::query('select version() as version');
        $server_info = array(
            'ThinkPHP版本' => 'ThinkPHP ' . THINK_VERSION,
            'CMS信息' => '作者 : <a target="new" href="https://www.newday.me">哩呵</a> , GIT : <a target="new" href="https://github.com/newday-me/think-cms">NewdayCms</a>。',
            '操作系统' => PHP_OS,
            '主机名信息' => $request->server('SERVER_NAME') . ' (' . $request->server('SERVER_ADDR') . ':' . $request->server('SERVER_PORT') . ')',
            '运行环境' => $request->server('SERVER_SOFTWARE'),
            'PHP运行方式' => php_sapi_name(),
            '程序目录' => WEB_PATH,
            'MYSQL版本' => 'MYSQL ' . $mysql_version[0]['version'],
            '上传限制' => ini_get('upload_max_filesize'),
            'POST限制' => ini_get('post_max_size'),
            '最大内存' => ini_get('memory_limit'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '内存使用' => CommonLogic::formatBytes(@memory_get_usage()),
            '磁盘使用' => CommonLogic::formatBytes(@disk_free_space(".")) . '/' . CommonLogic::formatBytes(@disk_total_space(".")),
            'display_errors' => ini_get("display_errors") == "1" ? '√' : '×',
            'register_globals' => get_cfg_var("register_globals") == "1" ? '√' : '×',
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? '√' : '×',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? '√' : '×'
        );
        $this->assign('server_info', $server_info);
        
        // 扩展列表
        $extensions_list = get_loaded_extensions();
        $this->assign('extensions_list', implode(' , ', $extensions_list));
        
        return $this->fetch();
    }

    /**
     * 账号设置
     */
    public function account(Request $request)
    {
        $this->site_title = '账号设置';
        return $this->fetch();
    }

    /**
     * 保存账号
     */
    public function saveAccount(Request $request)
    {
        $data = [
            'user_nick' => $request->param('user_nick'),
            'user_passwd' => $request->param('user_passwd'),
            're_passwd' => $request->param('re_passwd')
        ];
        
        // 验证数据
        $validate = new MemberValidate();
        $scene = empty($data['user_passwd']) ? 'account_info' : 'account_passwd';
        $res = $validate->scene($scene)->check($data);
        if (! $res) {
            return $this->error($validate->getError());
        }
        
        $map = [
            'id' => $this->user_id
        ];
        MemberLogic::saveMember($data, $map);
        return $this->success('修改资料成功', Url::build('index/account'));
    }
}
