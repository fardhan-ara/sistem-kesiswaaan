<!-- Modal Biodata Ortu -->
<?php if(!$siswa && (!isset($status) || $status === 'no_biodata' || $status === 'rejected')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#biodataModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#biodataModal').modal('show');
    });
</script>
<?php endif; ?>

<div class="modal fade" id="biodataModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Lengkapi Biodata Orang Tua</h5>
            </div>
            <form action="<?php echo e(route('biodata-ortu.store')); ?>" method="POST" enctype="multipart/form-data" id="formBiodata">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Informasi:</strong><br>
                        Email: <strong><?php echo e(Auth::user()->email); ?></strong><br>
                        Silakan lengkapi biodata untuk menghubungkan akun Anda dengan data siswa.
                    </div>

                    <div class="form-group">
                        <label>Nama Siswa (Anak) <span class="text-danger">*</span></label>
                        <input type="text" name="nama_siswa" class="form-control" placeholder="Masukkan nama lengkap anak" required>
                        <small class="text-muted">Nama harus sesuai dengan data siswa di sekolah</small>
                    </div>

                    <div class="form-group">
                        <label>NIS (Nomor Induk Siswa) <span class="text-danger">*</span></label>
                        <input type="text" name="nis" class="form-control" placeholder="Contoh: 23240001" required>
                        <small class="text-muted">NIS harus sesuai dengan data siswa di sekolah</small>
                    </div>

                    <hr>
                    <h6 class="text-primary"><i class="fas fa-users"></i> Data Orang Tua</h6>
                    <small class="text-muted">Isi minimal salah satu: Ayah, Ibu, atau Wali</small>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Ayah</label>
                                <input type="text" name="nama_ayah" class="form-control" placeholder="Nama lengkap ayah">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Telepon Ayah</label>
                                <input type="text" name="telp_ayah" class="form-control" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Ibu</label>
                                <input type="text" name="nama_ibu" class="form-control" placeholder="Nama lengkap ibu">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Telepon Ibu</label>
                                <input type="text" name="telp_ibu" class="form-control" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Wali</label>
                                <input type="text" name="nama_wali" class="form-control" placeholder="Nama lengkap wali">
                                <small class="text-muted">Isi jika yatim/piatu atau diasuh wali</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Telepon Wali</label>
                                <input type="text" name="telp_wali" class="form-control" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap tempat tinggal"></textarea>
                    </div>

                    <hr>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Dokumen Wajib untuk Verifikasi:</strong><br>
                        Upload Foto Kartu Keluarga (KK)
                    </div>

                    <div class="form-group">
                        <label>Foto Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                        <input type="file" name="foto_kk" class="form-control" accept="image/*,.pdf" required>
                        <small class="text-muted">Format: JPG, PNG, PDF. Max: 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <i class="fas fa-paper-plane"></i> Kirim Biodata untuk Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#formBiodata').on('submit', function() {
    $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/biodata_ortu/modal.blade.php ENDPATH**/ ?>