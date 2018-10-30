<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('booksmodel','books');
		$this->load->helper('url');

	}

	public function index() {

		$this->load->view('vbooks');
	}

	public function bookList() {
		$list = $this->books->get_datatables();
		$data = array();
		
		foreach ($list as $books) {
			
			$row = array();
			// $row[] = $books->id;
			$row[] = $books->title;
			$row[] = $books->author;
			$row[] = $books->date_published;
			$row[] = $books->total_pages;
			$row[] = $books->book_type;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_books('."'".$books->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_books('."'".$books->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
					"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function submitAdd() {
		$data = array(
						'title'  =>$this->input->post('booktitle'),
						'author' =>$this->input->post('authorname'),
						'date_published' =>$this->input->post('pubdate'),
						'total_pages' =>$this->input->post('totalpages'),
						'book_type' =>$this->input->post('booktype'),
					  );
		$insert = $this->books->save($data);
		echo json_encode(array("status"=>TRUE));
	}

	public function editData($id) {
		$data = $this->books->get_by_id($id);
		echo json_encode($data);
	}

	public function submitUpdate(){
		$data = array(
						'title'  =>$this->input->post('booktitle'),
						'author' =>$this->input->post('authorname'),
						'date_published' =>$this->input->post('pubdate'),
						'total_pages' =>$this->input->post('totalpages'),
						'book_type' =>$this->input->post('booktype'),
					  );
		$this->books->update(array('id'=>$this->input->post('id')), $data);
		echo json_encode(array('status'=>TRUE));
	}

	public function deleteData($id) {
		$this->books->delete_by_id($id);
		echo json_encode(array("status"=>TRUE));
	}
}