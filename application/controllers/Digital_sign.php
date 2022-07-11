<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Digital_sign extends CI_Controller
{
  public function index()
  {
    $data = [
      'title' => 'Digital Signature'
    ];
    $this->load->view('digital_sign', $data);
  }
  
  public function sign()
  {
    $this->load->library('ciqrcode');
    $output = [
      'status'  => false,
      'message' => '',
      'error'   => ''
    ];
  
    $id   = $this->input->post('id');
  
    $config['upload_path']          = './ttd/belum_ttd/';
    $config['allowed_types']        = 'pdf';
    $config['file_name']            = 'Dokumen Contoh';
    $config['overwrite']            = true;
    $config['max_size']             = 1024;
  
    $this->load->library('upload', $config);
  
    if (!$this->upload->do_upload($id)) {
      $output['status']   = false;
      $output['message']  = $this->upload->display_errors();
    } else {
      $upload = ['upload_data' => $this->upload->data()];
      $file_name = $upload['upload_data']['file_name'];
      $token     = substr(md5($file_name), 0, 10);
      $qr_image  = strtotime(date('Y-m-d H:i')) . '-' . strtoupper($token) . '.png';
      $file_names = explode('.pdf', $file_name);
  
      $params['data']     = site_url('ttd/sudah_ttd/' . $file_names[0] . '_signed.pdf');
      $params['level']    = 'H';
      $params['size']     = 4;
      $params['savename'] = FCPATH . "ttd/qrcode/" . $qr_image;
      $this->ciqrcode->generate($params);
  
      $path_jar = APPPATH . 'third_party/jsignpdf/JSignPdf.jar';
  
      $path_pdf = realpath('./ttd/belum_ttd/' . $file_name);
  
      $path_p12 = realpath('./ttd/p12/KLINIKCODE-2020-07-22-170537.p12');
      $visualisasi = realpath('./ttd/qrcode/' . $qr_image);
      $passphrase = 'password';
      $output_path = realpath('./ttd/sudah_ttd/');
  
      $llx = 64.57554;
      $lly = 687.8667;
      $urx = 249.4964;
      $ury = 616.0;
  
      if (file_exists($path_jar)) {
        $output['java'] = "Java Valid";
      }
      if (file_exists($path_pdf)) {
        $output['pdf'] = "Dokumen Valid";
      }
      if (file_exists($path_p12)) {
        $output['sertifikat'] = "Sertifikat Valid";
      }
      if (!file_exists($path_p12)) {
        $output['status_sertifikat'] = "Sertifikat Tidak Ditemukan !";
      }
  
      if (!($cert_store = file_get_contents($path_p12))) {
        $output['read_sertifikat'] = "Sertifikat tidak bisa dibaca !";
      }
  
      if (!openssl_pkcs12_read($cert_store, $cert_info, $passphrase)) {
        $output['pass_key'] = "Pass Key Salah !";
      }
  
      $first = 1;
  
      $command = 'java -jar "' . $path_jar . '" "' . $path_pdf . '" -kst PKCS12 -ksf "' . $path_p12 . '" -ksp "' . $passphrase . '" -tsh SHA256 -ha SHA256 -d "' . $output_path . '" -os "_signed" -llx ' . $llx . ' -lly ' . $lly . ' -urx ' . $urx . ' -ury ' . $ury . ' --l4-text "" --l2-text "" --bg-scale -1 --bg-path "' . $visualisasi . '" -r "Dokumen telah ditandatangani secara elektronik" -l "Padang" -c "https://klinikcode.com" --page ' . $first . ' -V -v';
  
      exec($command, $val, $er);
  
      if ($er == 0 || $er == 3) {
        $output['status'] = true;
        @unlink($visualisasi);
      } else {
        $output['error']    = $er;
        $output['status']   = false;
      }
    }
  
    echo json_encode($output);
  }
}