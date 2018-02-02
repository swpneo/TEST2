<?php
namespace app\index\controller;
use think\Request;
use think\Controller;
use app\common\model\Teacher;

class TeacherController extends Controller
{
    public function index(){
    try
    	{
    	$name = Request::instance()->get('name');
        $pageSize=5;
    	$Teacher = new Teacher;
    	if(!empty($name)){
    		$Teacher->where('name','like','%'.$name.'%');
    	}
    	$teachers=$Teacher->paginate($pageSize,false,['query=>[
    		'name'=>$name],]);
        
    	
    	$this->assign('teachers',$teachers);

    	$htmls=$this->fetch();

    	return $htmls;
    	}catch(\Exception $e)
    	{
    		return '系统错误'.$e->getMessage();
    	}
    }

    public function insert(){
  		//获取post数据
    	try{
    	$postdata=Request::instance()->post();  
    	//实例化teacher类
    	$Teacher=new Teacher;
    	//为对象赋值
    	$Teacher->name=$postdata['name'];
    	$Teacher->username=$postdata['username'];
    	$Teacher->sex=$postdata['sex'];
    	$Teacher->email=$postdata['email'];
    	$Teacher->creat_time =$postdata['creat_time'];	
    	$result = $Teacher->validate(true)->save();
    	if(false===$result){
    		 $message = '新增失败:' . $Teacher->getError();
    		}else{
    			return $this->success('用户' . $Teacher->name . '新增成功。', url('index'));
    		}
    	}catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        }
    	catch(\Exception $e)
    	{
    	return $e->getMessage();
    	}
    	return $this->error($message);
    }

    public function add(){
    	try
    	{
    		$html=$this->fetch();
    		return $html;
    	}catch(\Exception $e)
    	{
    		return '系统错误' . $e->getMessage();
    	}

    }

    public function delete(){
    	//获取pathinfo的值
    	try {
            // 实例化请求类
            $Request = Request::instance();
            
            // 获取get数据
            $id = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if (0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }

            // 获取要删除的对象
            $Teacher = Teacher::get($id);

            // 要删除的对象存在
            if (is_null($Teacher)) {
                throw new \Exception('不存在id为' . $id . '的教师，删除失败', 1);
            }

            // 删除对象
            if (!$Teacher->delete()) {
                return $this->error('删除失败:' . $Teacher->getError());
            }

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 

        // 进行跳转 
        return $this->success('删除成功', $Request->header('referer')); 
     }

     public function edit(){
     	 try {
            // 获取传入ID
            $id = Request::instance()->param('id/d');

            // 判断是否成功接收
            if (is_null($id) || 0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }
            
            // 在Teacher表模型中获取当前记录
            if (null === $Teacher = Teacher::get($id))
            {
                // 由于在$this->error抛出了异常，所以也可以省略return(不推荐)
                $this->error('系统未找到ID为' . $id . '的记录');
            } 
            
            // 将数据传给V层
            $this->assign('Teacher', $Teacher);

            // 获取封装好的V层内容
            $htmls = $this->fetch();

            // 将封装好的V层内容返回给用户
            return $htmls;

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
 	}
 	public function update()
    {
    	 try {
            // 接收数据，取要更新的关键字信息
            $id = Request::instance()->post('id/d');

            // 获取当前对象
            $Teacher = Teacher::get($id);

            if (!is_null($Teacher)) {
                // 写入要更新的数据
                $Teacher->name = input('post.name');
                $Teacher->username = input('post.username');
                $Teacher->sex = input('post.sex');
                $Teacher->email = input('post.email');

                // 更新
                if (false === $Teacher->validate(true)->save()) {
                    return $this->error('更新失败' . $Teacher->getError());
                }
            } else {
                throw new \Exception("所更新的记录不存在", 1);   // 调用PHP内置类时，需要在前面加上 \ 
            }

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
        
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));
    }
 	
}


