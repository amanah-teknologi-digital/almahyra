<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctematikbulanan extends CI_Controller {

	var $data = array();
    var $active_accordion_bulan = 0;
    var $active_tab_kelas = 0;
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

		$this->data = array(
            'controller'=>'ctematikbulanan',
            'redirect'=>'tematik-bulanan',
            'title'=>'Tematik Bulanan',
            'parent'=>'rencana'
        );

        $this->active_accordion_bulan = $this->session->userdata('active_accordion_bulan');
        $this->active_tab_kelas = $this->session->userdata('active_tab_kelas');

		## load model here 
		$this->load->model('Mtematikbulan', 'TematikBulan');
		$this->load->model('Mtahun', 'Tahun');
	}

	public function index()	{	

		$data = $this->data;

		$data['list'] = $this->TematikBulan->getAll();
		$data['column'] = $this->TematikBulan->getColumn();
        $this->session->unset_userdata('active_accordion_bulan');
        $this->session->unset_userdata('active_tab_kelas');

		$this->load->view('inc/tematikbulan/list', $data);
	}

    public function lihatdata($tahun){
        $data = $this->data;

        $data['tema_tahun'] = $this->Tahun->getByID($tahun);
        $data['list_bulan'] = $this->TematikBulan->getAllBulanByTahun($tahun);
        $data_mingguan = $this->TematikBulan->getJadwalMingguanByTahun($tahun);
        $data_tanggal_disabled = $this->TematikBulan->getTanggalSelected($tahun);
        $data_tanggal_disabled = array_column($data_tanggal_disabled, 'tanggal');
        $this->session->unset_userdata('active_tab_kelas');

        $data_subtema = [];
        $data_tanggal_pelaksana = [];
        $data_is_hapus = [];
        foreach ($data_mingguan as $subtema){
            if (!empty($data_subtema[$subtema->id_temabulanan])) {
                $temp_subtema = array_column($data_subtema[$subtema->id_temabulanan], 'id_jadwalmingguan');
            }else{
                $temp_subtema = [];
            }
            if (empty($data_subtema) OR !in_array($subtema->id_jadwalmingguan, $temp_subtema)){
                $data_subtema[$subtema->id_temabulanan][] = [
                    'id_jadwalmingguan' => $subtema->id_jadwalmingguan,
                    'nama_subtema' => $subtema->nama_subtema,
                    'keterangan' => $subtema->keterangan,
                ];
            }

            if (!empty($subtema->is_inputjadwalharian)){
                $data_is_hapus[$subtema->id_jadwalmingguan] = 1;
            }

            $data_tanggal_pelaksana[$subtema->id_jadwalmingguan][] = [
                'id_rincianjadwal_mingguan' => $subtema->id_rincianjadwal_mingguan,
                'tanggal' => $subtema->tanggal,
                'is_inputjadwalharian' => $subtema->is_inputjadwalharian,
                'created_at' => $subtema->created_at,
                'updated_at' => $subtema->updated_at,
                'nama_user' => $subtema->nama_user,
                'nama_role' => $subtema->nama_role,
            ];
        }

        $data['tahun_tematik'] = $tahun;
        $data['data_subtema'] = $data_subtema;
        $data['data_mingguan'] = $data_tanggal_pelaksana;
        $data['data_tanggal_disabled'] = $data_tanggal_disabled;
        $data['data_is_hapus'] = $data_is_hapus;
        $data['active_accordion_bulan'] = empty($this->active_accordion_bulan)? 0 : $this->active_accordion_bulan;

        $this->load->view('inc/tematikbulan/lihat_data', $data);
    }

	public function insert() {
        try {
            $this->TematikBulan->insert();

            $this->session->set_flashdata('success', 'Berhasil Tambah Data');
        } catch (Exception $e) {
            $this->session->set_flashdata('failed', 'Gagal Tambah Data: ' . $e->getMessage());
        }

		redirect($this->data['redirect'].'/'.$_POST['tahun_penentuan']);
	}

    public function insertsubtema() {
        try {
            $this->TematikBulan->insertSubTema();
            $bulan = $this->TematikBulan->getBulanBySubTema($_POST['id_temabulanan']);
            $this->session->set_userdata('active_accordion_bulan', $bulan);

            $this->session->set_flashdata('success', 'Berhasil Tambah Data');
        } catch (Exception $e) {
            $this->session->set_flashdata('failed', 'Gagal Tambah Data: ' . $e->getMessage());
        }

		redirect($this->data['redirect'].'/'.$_POST['tahun_penentuan']);
	}

    public function updatesubtema() {
        try {
            $this->TematikBulan->updateSubTema();
            $bulan = $this->TematikBulan->getBulanByIdJadwalMingguan($_POST['id_jadwalmingguan']);
            $this->session->set_userdata('active_accordion_bulan', $bulan);

            $this->session->set_flashdata('success', 'Berhasil Update Data');
        } catch (Exception $e) {
            $this->session->set_flashdata('failed', 'Gagal Update Data: ' . $e->getMessage());
        }

		redirect($this->data['redirect'].'/'.$_POST['tahun_penentuan']);
	}

	public function edit($id) {
		$data = $this->data;

		$data['list_edit'] = $this->TematikBulan->getByID($id) ;

	    $this->output->set_content_type('application/json');
	    
	    $this->output->set_output(json_encode($data));

	    return $data;
	}

    public function editsubtema($id) {
		$data = $this->data;

		$data['list_edit'] = $this->TematikBulan->getJadwalMingguanById($id) ;
        $tahun = $this->TematikBulan->getTahunByIdJadwalMingguan($id);
		$data_tanggal = $this->TematikBulan->getTanggalJadwalMingguan($id) ;
        $data_tanggal_disabled = $this->TematikBulan->getTanggalSelectedExcludeIdMingguan($tahun, $id);
        $data_tanggal_disabled = array_column($data_tanggal_disabled, 'tanggal');
        $data['data_tanggal_disabled'] = $data_tanggal_disabled;
        $data['list_jadwal_noneditable'] = [];
        $data['list_jadwal_editable'] = [];

        foreach ($data_tanggal as $tgl){
            if (empty($tgl->is_inputjadwalharian)){
                $data['list_jadwal_editable'][] = $tgl->tanggal;
            }else{
                $data['list_jadwal_noneditable'][] = $tgl->tanggal;
            }
        }

	    $this->output->set_content_type('application/json');

	    $this->output->set_output(json_encode($data));

	    return $data;
	}

	public function update() {
        try {
            $this->TematikBulan->update($this->input->post('id_temabulanan'));

            $this->session->set_flashdata('success', 'Berhasil Merubah Data');
        } catch (Exception $e) {
            $this->session->set_flashdata('failed', 'Gagal Merubah Data: ' . $e->getMessage());
        }

		redirect($this->data['redirect'].'/'.$_POST['tahun_penentuan']);
	}

	public function hapussubtema() {
        try {
            $bulan = $this->TematikBulan->getBulanByIdJadwalMingguan($_POST['id_jadwalmingguan']);
            $this->TematikBulan->hapusSubTema($_POST['id_jadwalmingguan']);
            $this->session->set_userdata('active_accordion_bulan', $bulan);

            $this->session->set_flashdata('success', 'Berhasil Hapus Data');
        } catch (Exception $e) {
            $this->session->set_flashdata('failed', 'Gagal Hapus Data: ' . $e->getMessage());
        }

        redirect($this->data['redirect'].'/'.$_POST['tahun_penentuan']);
	}
}
