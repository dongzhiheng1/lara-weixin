<?php

namespace App\Admin\Controllers;

use App\Model\UserModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;

class WeixinController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserModel);

        $grid->id('Id');
        $grid->openid('Openid');
        $grid->add_time('Add time')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });
        $grid->nickname('Nickname');
        $grid->sex('Sex')->display(function($sex){
            if($sex==1){
                return "男";
            }else{
                return "女";
            }
        });
        $grid->headimgurl('Headimgurl')->display(function($img){
            return "<img src='".$img."' width=100>";
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(UserModel::findOrFail($id));

        $show->id('Id');
        $show->openid('Openid');
        $show->add_time('Add time');
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->headimgurl('Headimgurl');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserModel);
        $form->text('openid', 'Openid');
        $form->number('add_time', 'Add time');
        $form->text('nickname', 'Nickname');
        $form->number('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        return $form;
    }
    public function sendAll(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body(view('admin.sendall'));
    }
    public function chatAll()
    {
        $access_token = UserModel::getAccessToken();
//        echo $access_token;die;
        $url ='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;
        $info=UserModel::get()->toArray();
        $openid=array_column($info,'openid');
//    print_r($openid);die;
        $message=$_POST['mes'];
//        echo $message;die;
        $data = [
            "touser" => $openid,
            "msgtype" => "text",
            "text" => [
                "content" =>$message
            ]
        ];
        $client = new GuzzleHttp\Client(['base_uri'=>$url]);
        $r = $client->request('POST', $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
        $respone_arr =json_decode($r->getBody(), true);

//       print_r($respone_arr);die;
//        return $data;
        if($respone_arr){
            echo "发送成功";
        }
    }
}
