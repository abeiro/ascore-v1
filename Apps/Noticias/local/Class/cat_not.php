<?php
function ListCat()
{
	$this->searchResults=$this->selectA();
	return $this->searchResults;
}
?>