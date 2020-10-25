<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class Stayhome extends Controller
{
    public $code;
    public $validate;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->code = config('code');
        $this->validate = validate('Stayhome');
    }

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        checkToken();
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = $this->request->get();
        if(isset($data['page']) && !empty($data['page'])){
            $page = $data['page'];
        }else{
            $page = 1;
        }

        if(isset($data['limit']) && !empty($data['limit'])){
            $limit = $data['limit'];
        }else{
            $limit = 5;
        }

        $where = [];
        if (isset($data['scity']) && !empty($data['scity'])){
            $where['scity'] = $data['scity'];
        }
        if (isset($data['sname']) && !empty($data['sname'])){
            $where['sname'] = ['like','%'.$data['sname'].'%'];
        }
        $result = Db::table('stayhome')->where($where)->paginate($limit,false,['page'=>$page]);
        $stayhome = $result->items();
        $total = $result->total();
        if ($stayhome && $total){
            return json([
               'code'=>200,
                'msg'=>'数据获取成功',
                'data'=>$stayhome,
                'total'=>$total
            ]);
        }else{
            return json([
                'code'=>200,
                'msg'=>'暂无数据',
            ]);
        }
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $this->request->post();
        $sname = $data['sname'];
        $isExist = Db::table('stayhome')->where('sname',$sname)->count();
        if ($isExist){
            return json([
                'code'=>$this->code['fail'],
                'msg'=>'该民宿名已存在'
            ]);
        }

        $data['ctime']=time();
        $result = Db::table('stayhome')->insert($data);
        if ($result){
            return json([
                'code'=>$this->code['success'],
                'msg'=>'数据添加成功'
            ]);
        }else{
            return json([
                'code'=>$this->code['fail'],
                'msg'=>'数据添加失败'
            ]);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = Db::table('stayhome')->where('sid',$id)->find();
        if ($data){
            $data['sbanner1'] = explode(',',$data['sbanner']);
            return json([
                'code'=>$this->code['success'],
                'msg'=>'数据获取成功',
                'data'=>$data
            ]);
        }else{
            return json([
                'code'=>$this->code['fail'],
                'msg'=>'暂无数据',
            ]);
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $sid)
    {
        $data = $this->request->put();
        $result = Db::table('stayhome')->where('sid',$sid)->update(['sname'=>$data['sname'],'sdesc'=>$data['sdesc'],'sthumb'=>$data['sthumb'],'sprice'=>$data['sprice'],'sprovince'=>$data['sprovince'],'scity'=>$data['scity'],'sarea'=>$data['sarea'],'saddress'=>$data['saddress'],'stag'=>$data['stag'],'sbanner'=>$data['sbanner'],'score'=>$data['score'],'sdetail'=>$data['sdetail'],'snotice'=>$data['snotice'],'cid'=>$data['cid'],'status'=>$data['status']]);
        if ($result){
            return json([
                'code'=>$this->code['success'],
                'msg'=>'数据修改成功',
                'data'=>$data
            ]);
        }else{
            return json([
                'code'=>$this->code['fail'],
                'msg'=>'数据修改失败',
            ]);
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $result = Db::table('stayhome')->where('sid',$id)->delete();
        if ($result){
            return json([
                'code'=>$this->code['success'],
                'msg'=>'数据删除成功',
            ]);
        }else{
            return json([
                'code'=>$this->code['fail'],
                'msg'=>'数据删除失败',
            ]);
        }
    }
}