<?php
/**
* 运营人员操作相关
* @author mingyang
* @since 2015-11-5
*/

class OperationController extends Controller{

	/**
	* 某个部门下角色的导出
	* @param $depart
	* @return array $roles
	*/
	public function ActionIndex(){
		$username = yii::app()->user->username;
		$request = yii::app()->request;
		$depart = $request->getParam('depart','规则与风控部－规则与风控');
		$export = $request->getParam('export',false);
		$allDepart = $this->operation->getExistDepart();
		$roles = '';
		$roles = $this->operation->getRolesInDepart($depart);
		if($depart&&$export){
			$this->operation->exportDepartExcel($roles,"部门角色导出");
		}else{
			$this->render('operation/index.tpl',array('roles'=>$roles,'alldepart'=>$allDepart,'depart'=>$depart));
		}
	}

}

?>
