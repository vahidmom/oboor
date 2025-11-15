<?= $this->extend('User/layout/master_login') ?>

<?= $this->section('content') ?>

        
        <!-- BEGIN WRAPPER -->
        <div class="fixed-modal-bg"></div>

        <div class="modal-page shadow two-section">
            <div class="container-fluid">                
                <div class="row">
                    

                    <div class="card shadow overflow-hidden">
                        <div class="row">
                            <div class="col-lg-5 col-md-6 position-relative bg-cover" style="background-image: url(<?= base_url('assets/images/other/mobile-auth.jpg') ?>);">
                                <div class="w-100 h-100 z-index-2 d-flex m-auto justify-content-center">
                                    <div class="mask bg-gradient-dark"></div>
                                    <div class="side-texts position-relative my-auto z-index-2">
                                        <div class="d-flex p-2 text-white my-3 bold">
                                           <?= esc($login_message) ?>
                                        </div>
                                    </div><!-- /.side-texts -->
                                </div><!-- /.w-100 -->
                            </div><!-- /.col -->
                            <div class="col-lg-7 col-md-6 py-5">
                             <form method="post" action="<?= site_url('user/login') ?>">
    <?= csrf_field() ?>
                                    <div class="card-header px-4 py-2 text-center">
                                        <div class="logo-con mb-4">
                                           
                                        </div><!-- /.logo-con -->

                                     
                                    </div>
                                    <div class="card-body pt-1">
                                        <div class="alert alert-success fill text-center bold" id="mainAlert">
                                           ایمیل و یا شماره همراه خود را وارد کنید
                                        </div>
 <?php if (session()->getFlashdata('error')): ?>
		<div class="alert alert-danger fill">
    <i class="icon-check"></i>
   <?= session()->getFlashdata('error') ?>
</div>
    <?php endif; ?>
	
                                        <div class="row form-group">
                                            <label for="txtCellphone">موبایل/ایمیل :</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="icon-screen-smartphone"></i>
                                                </span>
                                                <input type="text" name="input" class="form-control text-center ltr font-lg bold" value="<?= old('input') ?>" title="ایمیل و یا شماره همراه خود را وارد کنید"  required />
                                            </div><!-- /.input-group -->
                                            <div class="help-block"></div>
                                        </div><!-- /.row -->

                                      



                                        <div class="row">
                                            <div class="col-md-6 m-auto">
                                                <button type="submit" class="btn btn-success  mt-3 mb-0 w-100" >تایید و ادامه</button>
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.card-body -->
                                </form>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.card -->                    
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.modal-page -->
        <!-- END WRAPPER -->
        
<?= $this->endSection() ?>
