<?php 
/**
 * 
 */
$url = Router::parse();
$urlCat = false;
if (isset($url['category']))
	$urlCat = $url['category'];

$urlCol = false;
if (isset($url['collection']))
	$urlCol = $url['collection'];
	
$category = FiveTable::getInstance('categories');

$categories = $category->loadAll();
$category->loadByName( $urlCat );
$categoryUrl = 'categories';

$catList = array();
foreach ((array)$categories as $cat)
{
	if (in_array($cat->id, array('101','108'))) continue;
	
	$catList[$cat->id] = array();
	$catList[$cat->id]['name'] = strtoupper($cat->name);
	$catList[$cat->id]['url'] = array('controller' => 'categories');
	$catList[$cat->id]['url']['action'] = $cat->name;
}

//If we're being asked for a collection
if ($url['controller'] == $categoryUrl && $urlCat)
{
	$collection = FiveTable::getInstance('collections');
	$collections = $collection->loadAll( $urlCat );
	$categoryUrl = 'collections';
	
	$catList = array();
	foreach ((array)$collections as $col)
	{
		if (!$col) continue;
		
		$catList[$col->id] = array();
		$catList[$col->id]['name'] = strtoupper($col->name);
		$catList[$col->id]['url'] = array('controller' => 'categories', 'action' => $urlCat);
		$catList[$col->id]['url'][] = $col->name;
	}
	
	//If we're being asked for an assortment
	if ($urlCol)
	{
		$assortment = FiveTable::getInstance('assortments');
		$assortments = $assortment->loadByParents( $urlCat, $urlCol );
		$categoryUrl = 'assortments';

		$catList = array();
		foreach ((array)$assortments as $ass)
		{
			if (!$ass) continue;
			
			$catList[$ass->id] = array();
			$catList[$ass->id]['name'] = strtoupper($ass->name);
			$catList[$ass->id]['url'] = array('controller' => 'categories', 'action' => $urlCat, $urlCol, $ass->name);
		}
	}
	
}

?>
<div class="widget">
	<h2><?php echo ucwords($categoryUrl); ?></h2>
	<ul class="categories_list">
	<?php
	foreach ((array)$catList as $catId => $cat)
	{
		// class="active"
		?>
		<li>
			<a alt="<?php echo $cat['name']; ?>" title="<?php echo $cat['name']; ?>" target="_self" href="<?php echo Router::normalize( $cat['url'] ); //url('category/'.$s->name)//url('index.php?category='.$s->name); ?>">
			<?php echo $cat['name']; ?></a>
		</li>
		<?php
	}
	?>
	</ul>
</div>