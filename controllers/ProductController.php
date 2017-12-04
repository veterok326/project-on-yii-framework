<?php
namespace app\controllers;
use app\models\Category;
use app\models\Product;
use Yii;
class ProductController extends AppController
{
	public function actionView($id)
	{
		//$id = Yii::$app->request->get('id');
		$product = Product::findOne($id);//ленивая загрузка
		//$product = Product::find()->with('category')->where(['id' => $id])->limit(1)->one();
		if(empty($product)) throw new \yii\web\HttpException(404, 'Такого продукта нет');
		$this->setMeta('E-SHOPPER |'.$category->name , $category->keywords, $category->description);
		$this->setMeta('E-SHOPPER |'.$product->name , $product->keywords, $product->description);
		$hits = Product::find()->where(['hit' => '1'])->limit(6)->all();
		return $this->render('view', compact('product', 'hits'));
	}
}