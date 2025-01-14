<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabsensibarang extends CI_Controller {

    var $data = array();
    private $role;
    function __construct() {
        parent::__construct();
        
        if (empty($this->session->userdata['auth'])) {
            $this->session->set_flashdata('failed', 'Anda Harus Login');

            redirect('login');
        } else {
            // if($this->session->userdata['auth']->activation == 0 || $this->session->userdata['auth']->activation == '0') {
            //     redirect('profile');
            // }
        }

        $this->role = $this->session->userdata['auth']->id_role;

        $this->data = array(
            'controller'=>'cabsensibarang',
            'redirect'=>'absensi-barang',
            'title'=>'Absensi Barang Anak',
            'parent'=>'absensi',
            'role' => $this->session->userdata['auth']->id_role,
        );
        ## load model here 
        $this->load->model('Mabsensibarang', 'Absensibarang');
    }

    public function index() {

        $data = $this->data;

        $data['list'] = $this->Absensibarang->getListBarang($this->role);

        $this->load->view('inc/absensibarang/list', $data);
    }

    public function checkAktivitas(){
        $tanggal = $this->input->post('tanggal');
        $id_anak = $this->input->post('id_anak');

        $id_checkup  = $this->Absensibarang->checkAktivitas($tanggal, $id_anak);

        redirect($this->data['redirect'].'/lihat-data/'.$id_checkup);
    }

    public function lihatdata($id_absensi){
        $data = $this->data;

        $data['list_barang'] = $this->Absensibarang->getDataBarang();
        $data['data_checkup'] = $this->Absensibarang->getDataAbsensiBarang($id_absensi);
        $data['data_rinciancheckup'] = $this->Absensibarang->getDataRincianBarang($id_absensi);
        $data['id_absensi'] = $id_absensi;

        $this->load->view('inc/absensibarang/lihat_data', $data);
    }

    public function insert() {
        
        $err = $this->Absensibarang->insert();

        if ($err['code'] == '0') {
            $this->session->set_flashdata('success', 'Berhasil Menambahkan Data');
        } else {
            $this->session->set_flashdata('failed', 'Gagal Menambahkan Data');
        }

        redirect($this->data['redirect']);
    }

    public function getDataBarang($id) {
        $data = $this->Absensibarang->getDataBarang($id);

        $this->output->set_content_type('application/json');
        
        $this->output->set_output(json_encode($data));

        return $data;
    }

    public function insertbarang(){
        $err = $this->Absensibarang->insertBarang();

        if ($err === FALSE) {
            $this->session->set_flashdata('failed', 'Gagal Simpan Masuk');
        }else{
            $this->session->set_flashdata('success', 'Berhasil Simpan Masuk');
        }

        redirect($this->data['redirect'].'/lihat-data/'.$_POST['id_absensi']);
    }

    public function absenpulang(){
        $err = $this->Absensibarang->absenPulang();

        if ($err === FALSE) {
            $this->session->set_flashdata('failed', 'Gagal Absen Pulang');
        }else{
            $this->session->set_flashdata('success', 'Berhasil Absen Pulang');
        }

        redirect($this->data['redirect']);
    }
}
