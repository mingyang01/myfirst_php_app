<?php
class DocController extends Controller {

	private static $html = array('menu', 'auth', 'collection');
	public function actionIndex($name) {

		$new_name = "doc/$name.html";
		$filepath = Yii::getPathOfAlias('application.views') ."/". $new_name;

		if(file_exists($filepath)) {
			if (in_array($name, self::$html)) {
				echo file_get_contents($filepath);
			} else {
				$this->render($new_name);
			}
		} else {
			$this->redirect("/site/error");
		}

	}
}