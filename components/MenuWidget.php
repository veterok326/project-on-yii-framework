<?php
	namespace app\components;
	use yii\base\Widget;
	use app\models\Category;
	use Yii;
	class MenuWidget extends Widget
	{
		public $tpl;
		public $data;//массив категорий которые получим с категорий
		public $tree;//строит из обычного массива массив дерева
		public $menuHtml;//будет хранится готовый хтмл код в зависимости от файла тпл

		public function init()
		{
			parent::init();
			if($this->tpl === null)
			{
				$this->tpl = 'menu';
			}
			$this->tpl .= '.php';
		}
		public function run()
		{
			//get cache
			$menu = Yii::$app->cache->get('menu');
			if($menu) return $menu;
			$this->data =Category::find()->indexBy('id')->asArray()->all(); //получили массив
			$this->tree = $this->getTree(); //получили дерево и теперь нужно получить нужный хтмл код
			$this->menuHtml = $this->getMenuHtml($this->tree);
			//set cache
			Yii::$app->cache->set('menu', $this->menuHtml, 60);
			return $this->menuHtml;
		}
		protected function getTree()
		{//проходится по массиву и из одномерного массива строит дерево
			$tree = [];
			foreach ($this->data as $id=>&$node)
			{
				if(!$node['parent_id'])
					$tree[$id] = &$node;
				else
					$this->data[$node['parent_id']]['childs'][$node['id']] = $node;
			}
			return $tree;
		}
		protected function getMenuHtml($tree)
		{
			$str = '';
			//проходится в цикле по дереву и берет каждый конкретный элемент дерева и передает параметр его
			foreach($tree as $category)
			{
				$str .= $this->catToTemplate($category);
			}
			return $str;
		}
		protected function catToTemplate($category) //принимает параметром каждый элемент и помещает его в шаблон
		{
			ob_start();
			include __DIR__ . '/menu_tpl/'.$this->tpl;
			return ob_get_clean();
		}
	}