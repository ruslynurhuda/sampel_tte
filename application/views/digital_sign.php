<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  
  <title><?= $title; ?></title>
</head>
  
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-8">
        <div class="card mt-3">
          <div class="card-header">
            <?= $title; ?>
          </div>
          <div class="card-body">
            <form id="form-sign">
              <div class="form-group">
                <label for="file_pdf">File PDF</label>
                <input type="file" class="form-control" id="file_pdf" name="file_pdf" required>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Sign</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <iframe id="tampil_pdf" style="display: none;" src="" width="100%" height="768px"></iframe>
    </div>
  </div>
  
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  
  <script>
    function base_url(link = '') {
      let alamat = "<?= base_url(); ?>" + link;
      return alamat;
    }
    $('#form-sign').on('submit', function(e) {
      e.preventDefault();
      let form_data = new FormData(document.getElementById("form-sign"));
      let file_data = $('#file_pdf').prop('files')[0];
      form_data.append('file', file_data);
      form_data.append('id', 'file_pdf');
  
      $.ajax({
        url: "<?php echo base_url('digital_sign/sign'); ?>",
        dataType: 'json',
        mimeType: "multipart/form-data",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'POST',
        success: function(data) {
          console.log(data);
          if (data.status = true) {
            $('#tampil_pdf').show();
            $('#tampil_pdf').attr('src', base_url('ttd/sudah_ttd/Dokumen_Contoh_signed.pdf') + '#view=FitH');
          }
        }
      });
    });
  </script>
</body>
  
</html>