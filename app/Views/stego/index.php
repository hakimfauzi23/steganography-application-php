
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
      <div class="container">
        <?php
        if(!empty(session()->getFlashdata('success'))){ ?>
 
        <div class="alert alert-success">
            <?php echo session()->getFlashdata('success');?>
        </div>
             
        <?php } ?>
        <?php if(!empty(session()->getFlashdata('info'))){ ?>
 
        <div class="alert alert-info">
            <?php echo session()->getFlashdata('info');?>
        </div>
             
        <?php } ?>
 
        <?php if(!empty(session()->getFlashdata('warning'))){ ?>
 
        <div class="alert alert-warning">
            <?php echo session()->getFlashdata('warning');?>
        </div>
             
        <?php } ?>
    </div>
    <div class="container">
        <a href="<?php echo base_url('stego/create'); ?>" class="btn btn-warning float-right mb-3">Cyrpt Now!</a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead style='text-align:center'>
                    <th>Id</th>
                    <th>Pesan</th>
                    <th>Cipherteks</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php 
                    foreach($stego as $key => $data) { ?>
                    <tr>
                        <td style='text-align:center'><?php echo $data['id']; ?></td>
                        <td style='text-align:center'><?php echo $data['pesan']; ?></td>
                        <td style='text-align:center'><?php echo $data['cipher']; ?></td>
                        <td style='text-align:center'>
                            <div class="btn-group"  >
                                <a href="<?php echo base_url('stego/downloads/'.$data['gambar']); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i></a>
                                <a href="<?php echo base_url('stego/delete/'.$data['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk <?php echo $data['id']; ?> ini?')"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

