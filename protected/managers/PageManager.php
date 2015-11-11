<?php
/**
 * 分页类
 */
class PageManager extends Manager {

	/**
	 * 分页函数
	 * $allCount	总条数
	 * $nowPage		当前页
	 * $pageCount	分页数量
	 * $pageNum		显示页数数字
	 */
	public static function getPageInfo($totalCount, $nowPage=1, $pageCount=10, $pageNum=10){
		$pageInfo = array();
		$totalPage = 1;		//总页数
		$prevPage = 0;		//上一页
		$nextPage = 0;		//下一页
		$pageNumArr = array();	//当前分页数字

		$firstLink = '';	//首页链接
		$prevLink = '';		//上一页链接
		$nextLink = '';		//下一页链接
		$lastLink = '';		//最后页链接

		$totalPage = (int)ceil($totalCount/$pageCount);
		if ($nowPage > 1){
			$prevPage = $nowPage - 1;
		}else{
			$prevPage = 0;
		}
		if ($nowPage < $totalPage){
			$nextPage = $nowPage + 1;
		}else{
			$nextPage = $totalPage + 1;
		}

		//处理分页链接
		if (empty($_SERVER['QUERY_STRING'])){
			$queryStr = '';
		}else{
			parse_str($_SERVER['QUERY_STRING'], $queryArr);
			if (isset($queryArr['nowPage'])){
				unset($queryArr['nowPage']);
			}
			$queryStr = http_build_query($queryArr);
		}
		$firstLink = '?'.$queryStr.'&nowPage=1';
		$prevLink = '?'.$queryStr.'&nowPage='.$prevPage;
		$nextLink = '?'.$queryStr.'&nowPage='.$nextPage;
		$lastLink = '?'.$queryStr.'&nowPage='.$totalPage;

		$startNum = $nowPage - ((int)($pageNum/2)-1) > 0 ? $nowPage - ((int)($pageNum/2)-1) : 1;
		$endNum = $nowPage + ((int)($pageNum/2)+1) < $totalPage ? $nowPage + ((int)($pageNum/2)+1) : $totalPage;
		for ($i=$startNum; $i<=$endNum; $i++){
			$pageNumArr[$i] = '?'.$queryStr.'&nowPage='.$i;
		}

		$pageInfo['totalPage']	= $totalPage;
		$pageInfo['nowPage']	= $nowPage;
		$pageInfo['prevPage']	= $prevPage;
		$pageInfo['nextPage']	= $nextPage;
		$pageInfo['pageNumList']	= $pageNumArr;
		$pageInfo['firstLink']	= $firstLink;
		$pageInfo['prevLink']	= $prevLink;
		$pageInfo['nextLink']	= $nextLink;
		$pageInfo['lastLink']	= $lastLink;

		return $pageInfo;
	}
}
?>