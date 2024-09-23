 <div class="container-xl">
     <!-- Page title -->
     <div class="page-header d-print-none">
         <div class="row g-2 align-items-center">
             <div class="col d-flex align-items-center justify-content-between">
                 <h2 class="page-title">
                     <?= $page_title ?>
                 </h2>
                 <a href="<?= base_url('efiling/new-references') ?>" class="btn btn-secondary btn-sm">
                     <i class="fa fa-arrow-left"></i> Go Back
                 </a>
             </div>
         </div>
     </div>
 </div>
 <div class="page-body">
     <div class="container-xl">
         <!-- Content here -->
         <?php require_once(APPPATH . 'views/templates/components/new_reference_view_details.php') ?>

         <div class="card mb-3  mt-3">
             <div class="card-status-top bg-warning"></div>
             <div class="card-header">
                 <h3 class="card-title">Transaction Details</h3>
             </div>
             <div class="card-body">
                 <div class="table-responsive p-0">
                     <table class="table">
                         <thead>
                             <tr>
                                 <th>Transaction ID</th>
                                 <th>Amount</th>
                                 <th>Txn Date</th>
                                 <th>Payment Status</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php if (isset($transaction)) : ?>
                                 <tr>
                                     <td><?= $transaction['txn_id'] ?></td>
                                     <td><?= INDMoneyFormat($transaction['amount']) ?></td>
                                     <td><?= formatReadableDate($transaction['txn_date']) ?></td>
                                     <td><?= $transaction['payment_status_desc'] ?></td>
                                 </tr>
                             <?php endif; ?>
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>

     </div>
 </div>