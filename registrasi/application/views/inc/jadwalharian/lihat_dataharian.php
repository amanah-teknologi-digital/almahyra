<!DOCTYPE html>
<html lang="en" dir="/">

    <?php $this->load->view('layout/head') ?>

    <body class="text-left">
        <div class="app-admin-wrap layout-sidebar-compact sidebar-dark-purple sidenav-open clearfix">
            <?php $this->load->view('layout/navigation') ?>     

            <!-- =============== Horizontal bar End ================-->
            <div class="main-content-wrap d-flex flex-column">
                <?php $this->load->view('layout/header') ?>
                <!-- ============ Body content start ============= -->
                <div class="main-content">
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="#">Rencana Belajar</a></li>
                            <li><a href="#"><?= $title ?></a></li>
                            <li>Detail Jadwal</li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?= base_url().$redirect ?>" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                            <div class="card text-left">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h5 class="card-title text-center">Data Jadwal Harian untuk Sub Tema&nbsp;<span class="text-success"><?= $data_subtema->nama ?></span>&nbsp;pada Hari&nbsp;<span class="font-weight-bold"><?= format_date_indonesia($data_rincianjadwal_mingguan->tanggal).', '.date('d-m-Y', strtotime($data_rincianjadwal_mingguan->tanggal)); ?></span></h5>
                                        </div>
                                    </div>
                                    <br>
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <?php foreach ($data_kelas as $key => $kelas){
                                            if ($key == 0){
                                                if (empty($active_tab_kelas)) {
                                                    $active_tab_kelas = $kelas->id_kelas;
                                                }
                                            } ?>
                                            <li class="nav-item"><a class="nav-link <?= $active_tab_kelas==$kelas->id_kelas ?'active':''; ?>" data-toggle="tab" href="#tab<?= $kelas->id_kelas ?>" role="tab"><?= $kelas->nama ?></a></li>
                                        <?php } ?>
                                    </ul>

                                    <div class="tab-content" id="myTabContent">
                                        <?php foreach ($data_kelas as $key => $kelas){ ?>
                                            <div class="tab-pane fade show <?= $active_tab_kelas==$kelas->id_kelas ?'active':''; ?>" id="tab<?= $kelas->id_kelas ?>" role="tabpanel">
                                                <div class="card-body shadow">
                                                    <h5 class="card-title"><b><span class="fas fa-calendar"></span> Jadwal Harian</b></h5>
                                                    <?php echo form_open_multipart('', 'id="frm_gettemplate_jadwal'.$kelas->id_kelas.'"'); ?>
                                                        <div class="row mb-3 d-flex align-items-center justify-content-center">
                                                            <div class="col-sm-4">
                                                                <select name="template_jadwal" id="template_jadwal<?= $kelas->id_kelas ?>" class="form-control">
                                                                    <option value="">-- Pilih Template Jadwal --</option>
                                                                    <?php foreach ($data_template_jadwal as $template){ ?>
                                                                        <option value="<?= $template->id_templatejadwal ?>"><?= $template->nama ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <button class="btn btn-sm btn-warning" id="btn-tambahbytemplate<?= $kelas->id_kelas ?>"><span class="fas fa-plus"></span>&nbsp;Ambil dari Template Jadwal</button>
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <span class="btn btn-sm btn-primary btn-tambahkegiatan float-right" id="btn-tambahkegiatan<?= $kelas->id_kelas ?>" data-namatema="<?= $data_subtema->nama ?>" data-nama="<?= $kelas->nama  ?>" data-idkelas="<?= $kelas->id_kelas ?>"><span class="fas fa-plus"></span>&nbsp;Tambah Jadwal</span>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="table-responsive" id="zero_configuration_table<?= $kelas->id_kelas ?>">
                                                        <i class="text-muted" style="font-size: 11px"><b>note:</b> <span>* Jika tambah dari template, jadwal yang sudah ada akan dihapus!</span></i>
                                                        <table class="display table table-sm table-striped table-bordered">
                                                            <thead style="background-color: #bfdfff">
                                                                <tr>
                                                                    <th align="center">No</th>
                                                                    <th align="center">Jam</th>
                                                                    <th align="center">Kegiatan</th>
                                                                    <th align="center">Keterangan</th>
                                                                    <th align="center">Pilihan Standar</th>
                                                                    <th align="center">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (isset($data_jadwal_harian[$kelas->id_kelas])){
                                                                    foreach ($data_jadwal_harian[$kelas->id_kelas] as $key => $kegiatan){ ?>
                                                                        <tr>
                                                                            <td align="center"><?= $key+1; ?></td>
                                                                            <td nowrap align="center"><?= Date('H:i',strtotime($kegiatan->jam_mulai)).' - '.Date('H:i',strtotime($kegiatan->jam_selesai)) ?></td>
                                                                            <td nowrap><?= $kegiatan->uraian; ?></td>
                                                                            <td><span class="text-muted font-italic text-small"><?= $kegiatan->keterangan; ?></span></td>
                                                                            <td nowrap>
                                                                                <span class="text-muted font-italic text-small">
                                                                                    <?php $standar_pilihan = json_decode($kegiatan->standar_pilihan, true);
                                                                                    $jml_pil = count($standar_pilihan);
                                                                                    foreach ($standar_pilihan as $key => $value){
                                                                                        if ($key == $jml_pil-1){
                                                                                            echo $value;
                                                                                        }else{
                                                                                            echo $value.', ';
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </span>
                                                                            </td>
                                                                            <td nowrap align="center">
                                                                                <span class="btn btn-sm btn-warning edit_kegiatan" data-idkelas="<?= $kelas->id_kelas ?>" data-id="<?= $kegiatan->id_rincianjadwal_harian ?>" data-namatema="<?= $data_subtema->nama ?>" data-nama="<?= $kelas->nama  ?>"><span class="fas fa-edit"></span>&nbsp;Update</span>
                                                                                <span class="btn btn-sm btn-danger hapus_kegiatan" data-idkelas="<?= $kelas->id_kelas ?>" data-id="<?= $kegiatan->id_rincianjadwal_harian ?>" data-namatema="<?= $data_subtema->nama ?>" data-nama="<?= $kelas->nama  ?>" data-namakegiatan="<?= $kegiatan->uraian ?>"><span class="fas fa-times"></span>&nbsp;Hapus</span>
                                                                            </td>
                                                                        </tr>
                                                                <?php }
                                                                }else{ ?>
                                                                    <tr>
                                                                        <td colspan="6" align="center"><span class="font-weight-bold text-danger text-small"><i>Data Jadwal Kosong!</i></span></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <?php echo form_open_multipart($controller.'/terapkantemplatejadwal'); ?>
                                                        <div class="table-responsive" style="display: none;"  id="preview_fromtemplate<?= $kelas->id_kelas ?>">

                                                        </div>
                                                        <input type="hidden" name="id_rincianjadwal_mingguan" value="<?= $id_rincianjadwal_mingguan ?>">
                                                        <input type="hidden" name="tahun_penentuan" value="<?= $tahun_tematik ?>">
                                                    </form>
                                                </div>
                                                <br>
                                                <div class="card-body shadow">
                                                    <h5 class="card-title"><b><i class="fas fa-lightbulb"></i> Data Stimulus</b></h5>
                                                    <?php echo form_open_multipart('', 'id="frm_gettemplate_stimulus'.$kelas->id_kelas.'"'); ?>
                                                        <div class="row mb-3 d-flex align-items-center justify-content-center">
                                                            <div class="col-sm-4">
                                                                <select name="template_stimulus" id="template_stimulus<?= $kelas->id_kelas ?>" class="form-control">
                                                                    <option value="">-- Pilih Template Stimulus --</option>
                                                                    <?php foreach ($data_template_stimulus as $template){ ?>
                                                                        <option value="<?= $template->id_templatestimulus ?>"><?= $template->nama_template ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <button class="btn btn-sm btn-warning btn-tambahbytemplate_stimulus"><span class="fas fa-plus"></span>&nbsp;Ambil Template Stimulus</button>
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <span class="text-success font-italic" style="font-size: 11px;" id="label_load_stimulus<?= $kelas->id_kelas ?>"></span>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <i class="text-muted" style="font-size: 11px"><b>note:</b> <span>* Jika tambah dari template, data stimulus yang sudah ada akan direplace!</span></i>
                                                    <?php echo form_open_multipart($controller.'/simpanstimulus', 'id="frm_simpanstimulus'.$kelas->id_kelas.'"'); ?>
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <label>Fokus Stimulus</label>
                                                                <input type="text" name="nama_tema_stimulus" id="nama_tema_stimulus<?= $kelas->id_kelas ?>" class="form-control" autocomplete="off" value="<?= isset($data_jadwal_stimulus[$kelas->id_kelas])? $data_jadwal_stimulus[$kelas->id_kelas]->nama:'';  ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Uraian Kegiatan Stimulus</label>
                                                                <div id="editor<?= $kelas->id_kelas ?>" style="height: 200px;">
                                                                    <?= isset($data_jadwal_stimulus[$kelas->id_kelas])? $data_jadwal_stimulus[$kelas->id_kelas]->rincian_kegiatan:'';  ?>
                                                                </div>
                                                                <input type="hidden" name="editorContent" id="editorContent<?= $kelas->id_kelas ?>" data-editor-index="<?= $kelas->id_kelas ?>" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Keterangan <i>(Optional)</i></label>
                                                                <textarea class="form-control" name="keterangan" id="keterangan<?= $kelas->id_kelas ?>" cols="30" rows="5" autocomplete="off"><?= isset($data_jadwal_stimulus[$kelas->id_kelas])? $data_jadwal_stimulus[$kelas->id_kelas]->keterangan:'';  ?></textarea>
                                                            </div>
                                                        </fieldset>
                                                        <button class="btn btn-sm btn-success"><span class="fas fa-save"></span>&nbsp;Simpan Data Stimulus</button>
                                                        <input type="hidden" name="id_kelas" value="<?= $kelas->id_kelas; ?>">
                                                        <input type="hidden" name="id_rincianjadwal_mingguan" value="<?= $id_rincianjadwal_mingguan ?>">
                                                        <input type="hidden" name="tahun_penentuan" value="<?= $tahun_tematik ?>">
                                                    </form>
                                                </div>
                                                <br>
                                                <div class="card-body shadow">
                                                    <h5 class="card-title"><b><i class="fas fa-burger"></i> Data Feeding Menu</b></h5>
                                                    <?php echo form_open_multipart($controller.'/updatefeedingmenu', 'id="frm_feedingmenu'.$kelas->id_kelas.'"'); ?>
                                                    <fieldset>
                                                        <div class="form-group">
                                                            <label>Uraian Feeding Menu</label>
                                                            <div id="editor2<?= $kelas->id_kelas ?>" style="height: 200px;">
                                                                <?= isset($data_feeding_menu[$kelas->id_kelas])? $data_feeding_menu[$kelas->id_kelas]->uraian:'';  ?>
                                                            </div>
                                                            <input type="hidden" name="editorContent" id="editorContent2<?= $kelas->id_kelas ?>" data-editor-index="<?= $kelas->id_kelas ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Keterangan <i>(Optional)</i></label>
                                                            <textarea class="form-control" name="keterangan" id="keterangan2<?= $kelas->id_kelas ?>" cols="30" rows="5" autocomplete="off"><?= isset($data_feeding_menu[$kelas->id_kelas])? $data_feeding_menu[$kelas->id_kelas]->keterangan:'';  ?></textarea>
                                                        </div>
                                                    </fieldset>
                                                    <button class="btn btn-sm btn-success"><span class="fas fa-save"></span>&nbsp;Simpan Feeding Menu</button>
                                                    <input type="hidden" name="id_kelas" value="<?= $kelas->id_kelas; ?>">
                                                    <input type="hidden" name="id_rincianjadwal_mingguan" value="<?= $id_rincianjadwal_mingguan ?>">
                                                    <input type="hidden" name="tahun_penentuan" value="<?= $tahun_tematik ?>">
                                                    </form>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <p class="font-italic float-right mt-5"><span class="fas fa-info-circle"></span>&nbsp;<span class="text-muted" style="font-size: 11px">Jadwal harian dan stimulus bisa ditambahkan secara <b>manual</b> atau <b>otomatis dari template harian</b> untuk <b>masing-masing kelas</b>.</span></p>
                                </div>
                            </div>
                        </div>
                        <!-- end of col-->
                    </div>
                    <!-- end of main-content -->
                </div><!-- Footer Start -->

                <div class="modal fade" id="tambah-kegitan" tabindex="-1" role="dialog" aria-labelledby="adding" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <?php echo form_open_multipart($controller.'/insertkegiatan', 'id="frm_tambahkegiatan"'); ?>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Jadwal Kegiatan Sub Tema <span class="text-success" id="label_nama_subtema_tambah"></span></h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label>Nama Kelas</label>
                                        <p class="font-weight-bold" id="label_namakelas_tambah"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Jam Mulai</label>
                                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Jam Selesai</label>
                                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Standarisasi Pilihan</label>
                                        <select name="standarisasi[]" id="standarisasi" class="form-control tagselect" multiple="multiple" required>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Kegiatan</label>
                                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Keterangan <i>(Optional)</i></label>
                                        <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5" autocomplete="off"></textarea>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                <button class="btn btn-primary ml-2" type="submit">Simpan</button>
                            </div>
                        </div>
                        <input type="hidden" name="id_kelas" id="id_kelas_tambah">
                        <input type="hidden" name="id_rincianjadwal_mingguan" value="<?= $id_rincianjadwal_mingguan ?>">
                        <input type="hidden" name="tahun_penentuan" value="<?= $tahun_tematik ?>">
                        </form>
                    </div>
                </div>
                <div class="modal fade" id="update-kegitan" tabindex="-1" role="dialog" aria-labelledby="adding" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <?php echo form_open_multipart($controller.'/updatekegiatan', 'id="frm_updatekegiatan"'); ?>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pembaharuan Jadwal Kegiatan Sub Tema <span class="text-success" id="label_nama_subtema_update"></span></h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label>Nama Kelas</label>
                                        <p class="font-weight-bold" id="label_namakelas_update"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Jam Mulai</label>
                                        <input type="time" name="jam_mulai" id="jam_mulai_update" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Jam Selesai</label>
                                        <input type="time" name="jam_selesai" id="jam_selesai_update" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Kegiatan</label>
                                        <input type="text" name="nama_kegiatan" id="nama_kegiatan_update" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Standarisasi Pilihan</label>
                                        <select name="standarisasi_update[]" id="standarisasi_update" class="form-control tagselectupdate" multiple="multiple" required>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Keterangan <i>(Optional)</i></label>
                                        <textarea class="form-control" name="keterangan" id="keterangan_update" cols="30" rows="5" autocomplete="off"></textarea>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                <button class="btn btn-primary ml-2" type="submit">Simpan</button>
                            </div>
                        </div>
                        <input type="hidden" name="id_kelas" id="id_kelas_update">
                        <input type="hidden" name="id_rincianjadwal_harian" id="id_rincianjadwal_harian">
                        <input type="hidden" name="id_rincianjadwal_mingguan" value="<?= $id_rincianjadwal_mingguan ?>">
                        <input type="hidden" name="tahun_penentuan" value="<?= $tahun_tematik ?>">
                        </form>
                    </div>
                </div>
                <div class="modal fade" id="hapus-kegiatan" tabindex="-1" role="dialog" aria-labelledby="adding" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <?php echo form_open_multipart($controller.'/hapuskegiatan', 'id="frm_hapuskegiatan"'); ?>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Hapus Jadwal Kegiatan Sub Tema <span class="text-success" id="label_nama_subtema_hapus"></span></h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah yakin menghapus kegiatan <span class="font-weight-bold" id="label_nama_kegiatan_hapus"></span> pada kelas <span class="font-weight-bold" id="label_nama_kelas_hapus"></span>? </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                <button class="btn btn-danger ml-2" type="submit">Hapus</button>
                            </div>
                        </div>
                        <input type="hidden" name="id_kelas" id="id_kelas_hapus">
                        <input type="hidden" name="id_rincianjadwal_harian" id="id_rincianjadwal_harian_hapus">
                        <input type="hidden" name="id_rincianjadwal_mingguan" value="<?= $id_rincianjadwal_mingguan ?>">
                        <input type="hidden" name="tahun_penentuan" value="<?= $tahun_tematik ?>">
                        </form>
                    </div>
                </div>
                <!--  Modal -->
                <?php $this->load->view('layout/footer') ?>
            </div>
        </div>
    </body>
    <?php $this->load->view('layout/custom') ?>
    <script src="<?= base_url().'dist-assets/'?>js/plugins/datatables.min.js"></script>
    <script src="<?= base_url().'dist-assets/'?>js/scripts/datatables.script.min.js"></script>
    <script type="text/javascript">
        let url = "<?= base_url().$controller ?>";
        let lis_kelas = <?= json_encode($data_kelas) ?>;
        let quill = [];
        let quill2 = [];
        $(document).ready(function() {
            $(".tagselect").select2({
                tags: true
            });

            $(".tagselectupdate").select2({
                tags: true
            });

            $.each(lis_kelas, function(index, value){
                quill[value.id_kelas] =
                    new Quill('#editor'+value.id_kelas, {
                        theme: 'snow',  // You can also choose 'bubble'
                        modules: {
                            toolbar: [
                                [{ 'header': '1'}, {'header': '2'}, { 'font': [] }],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['bold', 'italic', 'underline'],
                                [{ 'align': [] }],
                                ['link'],
                                ['image'],
                                ['blockquote']
                            ]
                        }
                    });

                quill2[value.id_kelas] =
                    new Quill('#editor2'+value.id_kelas, {
                        theme: 'snow',  // You can also choose 'bubble'
                        modules: {
                            toolbar: [
                                [{ 'header': '1'}, {'header': '2'}, { 'font': [] }],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['bold', 'italic', 'underline'],
                                [{ 'align': [] }],
                                ['link'],
                                ['image'],
                                ['blockquote']
                            ]
                        }
                    });
            });

            $('.btn-tambahkegiatan').click(function(){
                clearFormStatus("#frm_tambahkegiatan");

                let nama_kelas = $(this).data('nama')
                let nama_tema = $(this).data('namatema')
                let id_kelas = $(this).data('idkelas')

                $("#label_nama_subtema_tambah").html(nama_tema);
                $("#label_namakelas_tambah").html(nama_kelas);
                $("#id_kelas_tambah").val(id_kelas);

                $("#tambah-kegitan").modal('show');
            });

            $('.edit_kegiatan').click(function(){
                clearFormStatus('#frm_updatekegiatan')

                let nama_kelas = $(this).data('nama')
                let nama_tema = $(this).data('namatema')
                let id_rincianjadwal_harian = $(this).data('id')
                let id_kelas = $(this).data('idkelas')

                $("#label_nama_subtema_update").html(nama_tema);
                $("#label_namakelas_update").html(nama_kelas);
                $("#id_rincianjadwal_harian").val(id_rincianjadwal_harian);
                $("#id_kelas_update").val(id_kelas);

                $.ajax({
                    url: url + '/editkegiatan/' + $(this).data('id'),
                    type:'GET',
                    dataType: 'json',
                    success: function(data){
                        let data_kegiatan = data['list_edit'];
                        let standar_pilihan = JSON.parse(data_kegiatan['standar_pilihan']);
                        standar_pilihan = Object.values(standar_pilihan);

                        $("#jam_mulai_update").val(data_kegiatan['jam_mulai']);
                        $("#jam_selesai_update").val(data_kegiatan['jam_selesai']);
                        $("#nama_kegiatan_update").val(data_kegiatan['uraian']);
                        $("#keterangan_update").val(data_kegiatan['keterangan']);

                        $('.tagselectupdate').empty();
                        $.each(standar_pilihan, function (i, item) {
                            $('.tagselectupdate').append($('<option>', {
                                value: item,
                                text : item
                            }));
                        });
                        $('.tagselectupdate').val(standar_pilihan).trigger('change');

                        $("#update-kegitan").modal('show');
                    }
                });
            });

            $('.hapus_kegiatan').click(function(){
                clearFormStatus("#frm_hapuskegiatan");

                let nama_kelas = $(this).data('nama')
                let nama_kegiatan = $(this).data('namakegiatan')
                let nama_tema = $(this).data('namatema')
                let id_rincianjadwal_harian = $(this).data('id')
                let id_kelas = $(this).data('idkelas')

                $("#label_nama_subtema_hapus").html(nama_tema);
                $("#label_nama_kegiatan_hapus").html(nama_kegiatan);
                $("#label_nama_kelas_hapus").html(nama_kelas);
                $("#id_rincianjadwal_harian_hapus").val(id_rincianjadwal_harian);
                $("#id_kelas_hapus").val(id_kelas);

                $("#hapus-kegiatan").modal('show');
            });

            $("#frm_tambahkegiatan").validate({
                rules: {
                    jam_mulai: {
                        required: true
                    },
                    jam_selesai: {
                        required: true
                    },
                    nama_kegiatan: {
                        required: true
                    }
                },
                messages: {
                    jam_mulai: {
                        required: "Jam mulai harus diisi!"
                    },
                    jam_selesai: {
                        required: "Jam selesai harus diisi!"
                    },
                    nama_kegiatan: {
                        required: "Nama Kegiatan harus diisi!"
                    }
                },
                submitHandler: function(form) {
                    form.submit(); // Mengirimkan form jika validasi lolos
                }
            });

            $.each(lis_kelas, function(index, value){
                $("#frm_simpanstimulus"+value.id_kelas).validate({
                    rules: {
                        nama_tema_stimulus:{
                            required: true
                        }
                    },
                    messages: {
                        nama_tema_stimulus: {
                            required: "Nama Tema Stimulus harus diisi!"
                        }
                    },
                    submitHandler: function(form, event) {
                        let content = quill[value.id_kelas].getText().trim();
                        let htmlcontent = quill[value.id_kelas].root.innerHTML;

                        if (htmlcontent === "<p><br></p>" || content === ""){
                            alert('Uraian Kegiatan Stimulus harus diisi!');
                            event.preventDefault();  // Prevent form submission
                            return false;  // Prevent default action
                        }

                        $('#editorContent'+value.id_kelas).val(htmlcontent);
                        form.submit(); // Mengirimkan form jika validasi lolos
                    }
                });

                $("#frm_feedingmenu"+value.id_kelas).validate({
                    rules: {},
                    messages: {},
                    submitHandler: function(form, event) {
                        let content = quill2[value.id_kelas].getText().trim();
                        let htmlcontent = quill2[value.id_kelas].root.innerHTML;

                        if (htmlcontent === "<p><br></p>" || content === ""){
                            alert('Uraian Feeding Menu harus diisi!');
                            event.preventDefault();  // Prevent form submission
                            return false;  // Prevent default action
                        }

                        $('#editorContent2'+value.id_kelas).val(htmlcontent);
                        form.submit(); // Mengirimkan form jika validasi lolos
                    }
                });
            });

            $("#frm_updatekegiatan").validate({
                rules: {
                    jam_mulai: {
                        required: true
                    },
                    jam_selesai: {
                        required: true
                    },
                    nama_kegiatan: {
                        required: true
                    }
                },
                messages: {
                    jam_mulai: {
                        required: "Jam mulai harus diisi!"
                    },
                    jam_selesai: {
                        required: "Jam selesai harus diisi!"
                    },
                    nama_kegiatan: {
                        required: "Nama Kegiatan harus diisi!"
                    }
                },
                submitHandler: function(form) {
                    form.submit(); // Mengirimkan form jika validasi lolos
                }
            });

            $.each(lis_kelas, function(index, value){
                $("#frm_gettemplate_jadwal"+value.id_kelas).validate({
                    rules: {
                        template_jadwal: {
                            required: true
                        }
                    },
                    messages: {
                        template_jadwal: {
                            required: "Template jadwal harian harus dipilih!"
                        }
                    },
                    submitHandler: function(form) {
                        let id_template = $("#template_jadwal"+value.id_kelas).val();
                        getDataJadwal(value.id_kelas, id_template);
                        //form.submit(); // Mengirimkan form jika validasi lolos
                    }
                });
            });

            $.each(lis_kelas, function(index, value){
                $("#frm_gettemplate_stimulus"+value.id_kelas).validate({
                    rules: {
                        template_stimulus: {
                            required: true
                        }
                    },
                    messages: {
                        template_stimulus: {
                            required: "Template stimulus harus dipilih!"
                        }
                    },
                    submitHandler: function(form) {
                        let id_template = $("#template_stimulus"+value.id_kelas).val();
                        getDataStimulus(value.id_kelas, id_template);
                        //form.submit(); // Mengirimkan form jika validasi lolos
                    }
                });
            });
        });

        function hideTombolManual(id_kelas){
            $("#template_jadwal"+id_kelas).prop('disabled', true);
            $("#btn-tambahbytemplate"+id_kelas).hide();
            $("#btn-tambahkegiatan"+id_kelas).hide();
        }

        function showTombolManual(id_kelas){
            $("#template_jadwal"+id_kelas).prop('disabled', false);
            $("#btn-tambahbytemplate"+id_kelas).show();
            $("#btn-tambahkegiatan"+id_kelas).show();
            $("#preview_fromtemplate" + id_kelas).html('');
            $("#preview_fromtemplate" + id_kelas).hide();
            $("#zero_configuration_table" + id_kelas).show()
        }

        function getDataJadwal(id_kelas, id_templatejadwal){
            $.ajax({
                url: url + '/gettemplatejadwal/' + id_templatejadwal,
                type:'GET',
                dataType: 'json',
                success: function(data){
                    let data_jadwal = data;
                    let temp_standar = '';

                    if (data_jadwal.length > 0) {
                        let html = '<p class="font-italic mt-3"><span class="fas fa-info-circle"></span>&nbsp;<span class="text-muted" style="font-size: 11px">Preview jadwal dari template, klik <b>tombol terapkan template</b> dibawah untuk <b>menerapkan jadwal template</b>.</span></p>';
                        html += '<table class="display table table-sm table-striped table-bordered font-italic">';
                        html += '<thead style="background-color: #bfdfff">';
                        html += '<tr>';
                        html += '<th align="center">No</th>';
                        html += '<th align="center">Jam</th>';
                        html += '<th align="center">Kegiatan</th>';
                        html += '<th align="center">Keterangan</th>';
                        html += '<th align="center">Pilihan Standar</th>';
                        html += '</tr>';
                        html += '</thead>';
                        html += '<tbody>';

                        $.each(data_jadwal, function (index, value) {
                            temp_standar = JSON.parse(value.standar_pilihan);
                            temp_standar = Object.values(temp_standar);

                            html += '<tr>';
                            html += '<td align="center">' + (index + 1) + '</td>';
                            html += '<td nowrap align="center">' + formatTime(value.jam_mulai) + ' - ' + formatTime(value.jam_selesai) + '</td>';
                            html += '<td nowrap>' + value.uraian + '</td>';
                            html += '<td><span class="text-muted font-italic text-small">' + value.keterangan + '</span></td>';
                            html += '<td nowrap"><span class="text-muted font-italic text-small">';
                            $.each(temp_standar, function (index, value) {
                                html += value + ', ';
                                if (index === temp_standar.length - 1) {
                                    html = html.slice(0, -2);
                                }
                            });
                            html += '</span></td>';
                            html += '</tr>';
                        });

                        html += '</tbody>';
                        html += '</table>';
                        html += '<input type="hidden" name="id_kelas" value="'+id_kelas+'">';
                        html += '<input type="hidden" name="id_templatejadwal" value="'+id_templatejadwal+'">';
                        html += '<button class="btn btn-sm btn-success"><span class="fas fa-check"></span>&nbsp;Terapkan Template</button>';
                        html += '&nbsp;<button class="btn btn-sm btn-danger" onclick="showTombolManual('+id_kelas+')"><span class="fas fa-times"></span>&nbsp;Batal</button>';

                        $("#preview_fromtemplate" + id_kelas).html(html);
                        $("#preview_fromtemplate" + id_kelas).show();
                        $("#zero_configuration_table" + id_kelas).hide()
                        hideTombolManual(id_kelas);
                    }else{
                        alert('Data jadwal template jadwal kosong!');
                    }
                }
            });
        }

        function getDataStimulus(id_kelas, id_templatestimulus){
            $.ajax({
                url: url + '/gettemplatestimulus/' + id_templatestimulus,
                type:'GET',
                dataType: 'json',
                success: function(data){
                    let data_jadwal = data;

                    if(data_jadwal){
                        $("#nama_tema_stimulus"+id_kelas).val(data_jadwal.nama);
                        quill[id_kelas].root.innerHTML = data_jadwal.uraian;
                        $("#keterangan"+id_kelas).val(data_jadwal.keterangan);
                        $("#label_load_stimulus"+id_kelas).html('Data berhasil diload!, silahkan klik tombol <b>Simpan</b> dibawah untuk menyimpan data!');
                    }else{
                        alert('Data template stimulus kosong!');
                    }
                }
            });
        }

        function formatTime(inputTime) {
            // Extract the hours and minutes from the input string (HH:MM:SS format)
            const [hours, minutes] = inputTime.split(':');

            // Return the formatted time as HH:MM
            return `${hours}:${minutes}`;
        }

        function clearFormStatus(formId) {
            // Reset the form values
            $(formId)[0].reset();

            // Clear validation messages and error/success classes
            $(formId).find('.valid').removeClass('valid'); // Remove valid class
            $(formId).find('label.error').remove(); // Remove error messages
            $(formId).find('.error').removeClass('error'); // Remove error class
        }
    </script>
</html>