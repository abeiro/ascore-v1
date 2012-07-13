<?php 

class psCarrito 
{

  var $ID;
  var $n_elementos;
  var $contenido;
  var $id_user;
  var $shop;
  

	function psCarrito($ID=0) {
			
		
			if (empty($_SESSION["cartpyme"])) {
				/* No ha sido inicializado el carrito */
				$this->contenido=array();
				$cartpyme=$this->contenido;
				$_SESSION["cartpyme"]=$cartpyme;
				//echo "Carrito nuevo";
			}
			else
			{
				/* El carrito ya ha sido inicializado */
				$this->contenido=$_SESSION["cartpyme"];
			}
			$this->n_elementos=sizeof($this->contenido);

		}


	

	function add($ID,$num=1,$shop=0) {
		

		if (empty($ID))
			return False;
		$this->contenido[$ID]+=abs($num);
		if ($shop!=0)
			$this->contenido["SHOP"]=$shop;
		$_SESSION["cartpyme"]=$this->contenido;
		$this->n_elementos=sizeof($this->contenido);
		
		return true;

	}
	
	function isEmpty() {
		
		if ($this->n_elementos==0)
			return True;
		if (($this->n_elementos==1)&&(isset($this->contenido["SHOP"])))
			return True;
		return False;
	
	
	}
	
	function update() {
		

		$_SESSION["cartpyme"]=$this->contenido;
				
		return true;

	}
	
	function addabsolute($ID,$num=1,$shop=0) {
		

		
		if (empty($ID))
			return False;
		if ($num==0)
			$this->remove($ID);
		else
			$this->contenido[$ID]=abs($num);
		if ($shop!=0)
			$this->contenido["SHOP"]=$shop;
		$_SESSION["cartpyme"]=$this->contenido;
		$this->n_elementos=sizeof($this->contenido);
		if ($this->isEmpty())
			$this->vaciar();
		return true;

	}
	function remove($ID) {
		

		
		if (empty($ID))
			return False;
		unset($this->contenido[$ID]);
		
		return true;

	}

	/**
  	* Borrar un elemento del carrito
   	*
   	* @param array par identificador,cantidad a borrar
   	* @param string $init
   	*
   	* @author Augusto Beiro <abeiro@activasistemas.com>
   	*/

	function del($d) {



			if (!isset($d["num"])||($d["num"]==0))
			$this->contenido["P".$d["ID"]]=-1;
		else
			$this->contenido["P".$d["ID"]]-=$d["num"];
		if ($this->contenido["P".$d["ID"]]<=0) {
			asort($this->contenido);
			array_shift($this->contenido);
		}

		$_SESSION["cartpyme"]=$this->contenido;

		return true;

	}
	/**
  	* Devuelve un array con los pares id,cantidad
   	*
   	*
   	* @author Augusto Beiro <abeiro@activasistemas.com>
   	*/

	function get() {

		global $array_sorted_and_cleaned;



		$array_sorted_and_cleaned=array();

		if (!function_exists("newarray")) {
			function newarray($val,$key){
				global $array_sorted_and_cleaned;
				$array_sorted_and_cleaned[str_replace ("P", "", $key)]=$val;
                }
		}


		array_walk($this->contenido,"newarray");

		asort($array_sorted_and_cleaned);
		return $array_sorted_and_cleaned;

	}
	function vaciar() {
		
		unset($this->contenido);	
		unset($this->n_elementos);
		$_SESSION["cartpyme"]="";

		return True;
	}

	/**
  	* Crea un pedido y el detalle de del mismo
   	* No llamar de forma externa
   	*
   	* @author Augusto Beiro <abeiro@activasistemas.com>
   	*/

/*
	function checkout($dirs) {

		global $auth;

		$elements=$this->get();
		
		if (empty($elements)) {
			echo "Carrito vacio";
			return False;
		}
		$pedido=newObject("Artepyme::psPedidos_recibidos");
		$fact=newObject("Artepyme::psFacturas_emitidas",1);
		$fact->fecha=time();
		$fact->concepto="Compra en CVC";
		$fact->recibida="NO";
		$fact->pagada="NO";
		$fact->user_id=$auth["user_id"];
		$fact->datos=$dirs[1];
		$fact->save();

		$pedido_data=array(
			"ID"=>"1",
			"id_factura"=>$fact->ID,
			"fecha"=>time(),
			"id_user"=>$auth["user_id"],
			"datos"=>$dirs[2],
			"estado"=>"Tramitado"
		);

		$res=$pedido->save($pedido_data);
		$origen=$pedido->ID;


		// Construimos el detalle del pedido
		//recibido. Esta información servirá para
		//crear el pedido emitido y sus detalles 

		$pac=array();
		foreach ($elements as $ID=>$cantidad) {

			$entry=newObject("Artepyme::psDetalle_pr",1);
			$item=newObject("Artepyme::psProductos",$ID);

			$entry->id_articulo=$ID;
			$entry->id_pedido=$pedido->ID;
			$entry->precio_venta=current($item->getPrecio());
			$entry->iva=next($item->getPrecio());			;
			$entry->cantidad=$cantidad;
			$entry->precio_venta_iva=$entry->precio_venta*($entry->iva/100)*$cantidad;
			$entry->precio_venta_total=$entry->precio_venta*($entry->iva/100+1)*$cantidad;
			$total+=$entry->precio_venta_total;
			$entry->estado="Tramitado";
			$pac[$item->id_prov]++;

			$entry->save();
		}
		$pedido_data["total"]=$total;
		$pedido_data["ID"]=$pedido->ID;
		$res=$pedido->save($pedido_data);
		$fact->importe=$total;
		$fact->save();

		//Construimos varios pedidos para emitir
		//según proveedor
		//
		
        foreach ($pac as $ID_prov=>$n ) {
			$pedido=newObject("Artepyme::psPedidos_emitidos");
			$fact=newObject("Artepyme::psFacturas_proveedor",1);
			$fact->fecha_factura=time();
			$fact->recibida="NO";
			$fact->pagada="NO";
			$fact->id_proveedor=$ID_prov;
        	$fact->save();
			$pedido_data=array(
			"ID"=>"1",
			"id_factura"=>$fact->ID,
			"fecha"=>time(),
			"id_prov"=>$ID_prov,
			"id_pedido"=>$origen,
			"datos"=>$dirs[2],
			"estado"=>"Tramitado"
			);
			$res=$pedido->save($pedido_data);
			foreach ($elements as $ID=>$cantidad) {

				$entry=newObject("Artepyme::psDetalle_pe",1);
				$item=newObject("Artepyme::psProductos",$ID);
 				if ($item->id_prov!=$ID_prov)
					continue;
				$entry->id_articulo=$ID;
				$entry->estado="Tramitado";
				$entry->id_pedido=$pedido->ID;
				$entry->precio_venta=current($item->getPrecio());			
				$entry->iva=next($item->getPrecio());
				$entry->cantidad=$cantidad;
				$entry->precio_venta_iva=$entry->precio_venta*($entry->iva/100)*$cantidad;
				$entry->precio_venta_total=$entry->precio_venta*($entry->iva/100+1)*$cantidad;
				$entry->estado="Tramitado";
				$entry->save();	
				$stats=newObject("Artepyme::psHit_stats");
				$stats->hit($ID);

			}
			$p=newObject("Artepyme::psPedidos_emitidos",$pedido->ID);
			$p->total=$p->calcTotal();
			$p->save();
			$fact->importe=$p->total;
			$fact->save();
			$this->vaciar();


		}

	}
*/
	function view() {

		global $auth;

		$elements=$this->get();
		
		if (empty($elements)) {
			echo "Carrito vacio";
			return False;
		}

	
		dataDump($this);		
			
			



	}


function block() {

		/*global $auth;

		$elements=$this->get();
		
		if (empty($elements)) {
			echo "Carrito vacio";
			$elements=array();
		}

		require_once("Lib/lib_planty.php");
		$pr=new plantilla("block_carrito");
		echo $pr->plParseTemplateHeader();

		ksort($elements,SORT_NUMERIC);

		foreach ($elements as $ID=>$cantidad) {

			$entry=newObject("Artepyme::psDetalle_pr",1);
			$item=newObject("Artepyme::psProductos",$ID);
		
			$entry->id_articulo=$ID;
			$entry->precio_venta=(float)current($item->getPrecio());			
			$entry->precio_oferta=(float)$item->pop;			
			$entry->cantidad=$cantidad;
			$entry->precio_venta_total=(float)$entry->precio_venta*$cantidad;
			$entry->iva=(float)next($item->getPrecio());
			$entry->iva2=(float)($entry->iva/100)*$entry->precio_venta_total;
			$entry->total=(float)$entry->iva2+$entry->precio_venta_total;
			$total+=(float)$entry->total;
			$total_iva+=(float)$entry->iva2;


			//dataDump($entry);
			$entry->nombre=$item->nombre;
			if (end($item->getPrecio())=="oferta")
				$entry->oferta="*";
			echo $pr->plParseTemplate($entry);

		}
		$totales=array(total=>$total,total_iva=>$total_iva);
   		echo $pr->plParseTemplateFooter($totales);
		*/
		dataDump($this);		
			
			



	}

	function vacio() {

		$elements=$this->get();
		
		if (empty($elements)) 
			return True;
		else
			return False;
	}

}

?>
