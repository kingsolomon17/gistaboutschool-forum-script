<?php 


//Pagination class

class pagination{

private $per_page;
private $page;
private $url;
private $total;



function __construct($per_page = 10, $page = 1, $url = '', $total){

$this->per_page = $per_page;
$this->page = ($page == 0 ? 1 : $page); 
$this->url = $url;
$this->total = $total;

}


private function preplace($url, $replace){

$result = str_replace('(page)', $replace, $url);
return $result;

}


public function display(){    
 
$adjacents = "2";
 
$start = ($this->page - 1) * $this->per_page;                              
         
$prev = $this->page - 1;                         
$next = $this->page + 1;
$lastpage = ceil($this->total/$this->per_page);
$lpm1 = $lastpage - 1;
         
$pagination = "";

if($lastpage > 1)
{  
$pagination .= "";
$pagination .= "(Page $this->page of $lastpage) ";
if ($lastpage < 7 + ($adjacents * 2))
{  
for ($counter = 1; $counter <= $lastpage; $counter++)
{
if ($counter == $this->page)
{
$pagination.= "<b>($counter)</b> ";
}
else
{
$pagination.= "<a href='".$this->preplace($this->url, $counter)."'>($counter)</a> ";
}

}
}
elseif($lastpage > 5 + ($adjacents * 2))
{
if($this->page < 1 + ($adjacents * 2))    
{
for ($counter = 1; $counter < 2 + ($adjacents * 2); $counter++)
{
if ($counter == $this->page)
{
$pagination.= "<b>($counter)</b> ";
}
else
{
$pagination.= "<a href='".$this->preplace($this->url, $counter)."'>($counter)</a> ";
}
}
$pagination.= "(..) ";
$pagination.= "<a href='".$this->preplace($this->url, $lpm1)."'>($lpm1)</a> ";
$pagination.= "<a href='".$this->preplace($this->url, $lastpage)."'>($lastpage)</a> ";     
}
elseif($lastpage - ($adjacents * 2) > $this->page && $this->page > ($adjacents * 2))
{
$pagination.= "<a href='".$this->preplace($this->url, 1)."'>(1)</a> ";
$pagination.= "<a href='".$this->preplace($this->url, 2)."'>(2)</a> ";
$pagination.= "(..) ";
for ($counter = $this->page - $adjacents; $counter <= $this->page + $adjacents; $counter++)
{
if ($counter == $this->page)
{
$pagination.= "<b>($counter)</b> ";
}
else
{
$pagination.= "<a href='".$this->preplace($this->url, $counter)."'>($counter)</a> ";
}

}
$pagination.= "(..) ";
$pagination.= "<a href='".$this->preplace($this->url, $lpm1)."'>($lpm1)</a> ";
$pagination.= "<a href='".$this->preplace($this->url, $lastpage)."'>($lastpage)</a> ";     
}
else
{
$pagination.= "<a href='".$this->preplace($this->url, 1)."'>(1)</a> ";
$pagination.= "<a href='".$this->preplace($this->url, 2)."'>(2)</a> ";
$pagination.= "(..) ";
for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
{
if ($counter == $this->page)
{
$pagination.= "<b>($counter)</b> ";
}
else
{
$pagination.= "<a href='".$this->preplace($this->url, $counter)."'>($counter)</a> ";
}

}
}
}
             
if ($this->page < $counter - 1){
$pagination.= "<a href='".$this->preplace($this->url, $lastpage)."'>(Last)</a>";
}else{
}
$pagination.= "";     
} 
return $pagination;
} 		

public function display2() {

$lastpage = ceil($this->total/$this->per_page);
$pagination = "";
if($lastpage > 7)
{
for ($counter = 2; $counter <= 4; $counter++)
{
$pagination .='<a href="'.$this->preplace($this->url, $counter).'">('.$counter.')</a> ';
}
$pagination .=" ... ";
for ($counter = ($lastpage - 2); $counter <= $lastpage; $counter++)
{
$pagination .='<a href="'.$this->preplace($this->url, $counter).'">('.$counter.')</a> ';
}
} else {
for ($counter = 2; $counter <= $lastpage; $counter++)
{
$pagination .='<a href="'.$this->preplace($this->url, $counter).'">('.$counter.')</a> ';
}
}
return $pagination;
}




}
?>	