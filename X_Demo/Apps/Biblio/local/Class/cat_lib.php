<?php
function ListCat()
{
	$this->searchResults=$this->selectA();
	return $this->searchResults;
}
function DeleteCat()
{
	$this->searchResults=$this->selectA();
	return $this->searchResults;
}
?>