<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct() {
		parent::__construct();
		$this->_public_view= $this->config->item('public_view');
	}

	public function index()
	{
		$data=array('page_content' => 'Dashboard.php');
		$this->load->view($this->_public_view,$data);
	}
	public function open()
	{
		$this->load->view('Dashboard.php');
	}

	public function query($query, $rank) {
		require_once('Rank-C/service.php');
		$query=strtolower($query);

		$a=run($query,$rank);

		$name=array();
		$rank=array();
		for ($i=5; $i < sizeof($a)-1 ; $i++) {
			$pieces= explode(" ",$a[$i]);
			if (strcmp($pieces[1],"0.000000")==0) {
				break;
			} else {
			array_push($name, $pieces[0]);
			array_push($rank, $pieces[1]);
			}
		}

		$time_temp = explode(" ",$a[sizeof($a)-1]);
		$time = "Time : <i>".$time_temp[2]."</i> mili seconds";

		$judul=array();
		$content=array();
		$content_temp=array();
		for ($i=0; $i < sizeof($name) ; $i++) {
			array_push($judul,$this->getTitle($name[$i]));
			array_push($content,$this->getContent($name[$i]));
		}

		for ($i=0; $i < sizeof($content) ; $i++) {
			array_push($content_temp,$this->cutContent($content[$i]));
		}

		$result=array();
		for ($i=0; $i < sizeof($judul) ; $i++) {
			array_push($result,'<div style ="margin-bottom:30px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			border-radius: 5px; padding: 10px;">
			<div style="color:#1a0dab; font-size:18pt; font-weight:bold;
			font-family: Times New Roman, Times, serif; text-transform: capitalize;">'.$judul[$i].'</div>
			<span style="font-size:9pt; color:#006621;">score: '.$rank[$i].'</span>
			<div style="text-align: justify; text-justify: inter-word; font-size:10pt; color:#434A54;"></div>'
			.$content_temp[$i].'</div>');
		}

		if (sizeof($judul)==0) {
			$found = "<h3>No Result Found</h3>";
		} else {
		$found = "<h5>Found : <i>".sizeof($judul)."</i> Result</h5>";
		}

		$combine=array($time,$result,$found);

		 echo json_encode($combine);

	}

	function getTitle($file) {
      $dom = new DOMDocument();
      libxml_use_internal_errors(true);
      $dom->loadHTMLFile("Rank-C/data/".$file);
      $nodes = $dom->getElementsByTagName('title');
      $title = $nodes->item(0)->nodeValue;
      libxml_clear_errors();
      return $title;
    }

    function getContent($file){
      $dom = new DOMDocument();
      libxml_use_internal_errors(true);
      $dom->loadHTMLFile("Rank-C/data/".$file);
      $nodes = $dom->getElementsByTagName('content');
      $content = $nodes->item(0)->nodeValue;
      libxml_clear_errors();
      return $content;
    }

    function cutContent($content){
    $arr=explode(" ",$content);
    $newContent="";

    if(count($arr)>=50){
      for ($i=0; $i < 50; $i++) {
        $newContent .= " ".$arr[$i];
      }
    }

    else{
      return $content;
    }

    return $newContent."...";
  }
}
