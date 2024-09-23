<div class="">
    <div class="content">
        <div class="content-heading text-center">
            <h4 class="content-title">
                <strong><?= DEPARTMENT_NAME ?></strong>
            </h4>
            <h5>
                <?= DEPARTMENT_ADDRESS ?>
            </h5>
            <h5>
                (Ph: <?= DEPARTMENT_PHONE ?>, Fax-<?= DEPARTMENT_FAX ?>, Website: <?= DEPARTMENT_WEBSITE ?>)
            </h5>
            <h5>
                E-mail: <?= DEPARTMENT_COMMON_EMAIL ?>
            </h5>
        </div>

        <div class="content-body">
            <p>To,</p>
            <table class="table w-100">
                <tbody>
                    <tr>
                        <td width="2%" valign="top"><strong>1.</strong></td>
                        <td width="50%" valign="top">
                            <?php $count = 'a'; ?>
                            <?php foreach ($parties as $key => $party) : ?>
                                <?php if ($party['type'] == 'claimant') : ?>
                                    <p><?= $count++ . ') ' . $party['name'] ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                        <td valign="top">
                            <strong>Petitionar</strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="2%" valign="top"><strong>2.</strong></td>
                        <td valign="top">
                            <?php $count = 'a'; ?>
                            <?php foreach ($parties as $key2 => $party) : ?>
                                <?php if ($party['type'] == 'respondant') : ?>
                                    <p><?= $count++ . ') ' . $party['name'] ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                        <td valign="top">
                            <strong>Respondent</strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p>
                <strong><u>In the matter of Arbitration between:</u></strong>
            </p>
            <p>
                <?= $parties[0]['name'] ?>
            </p>
            <p>
                <strong>AND</strong>
            </p>
            <p>
                <?= $parties[1]['name'] ?>
            </p>

            <p>
                <strong>
                    Case Ref. No. : <?= $case_data['case_no'] ?> (Quote this reference no for future communications with DIAC).
                </strong>
            </p>

            <p>
                Sir/Madam,
            </p>
            <p>
                As per the assessment dated ---------------- of -------------- in the above-noted matter, total Arbitrator’s fee, comes to Rs.---------------------------(Provisionally). Each party’s share towards Arbitrator’s fee therefore is Rs.------------------/- and Rs. ----------------/- towards miscellaneous expenses (each).
            </p>
            <p>
                As per records, the status of fee deposited by the parties, as on date, is as follows:
            </p>

            <table class="table w-100 table-bordered" cellspacing="0">
                <thead>
                    <tr>
                        <th width="25%">Party</th>
                        <th width="25%">Fee Payable</th>
                        <th width="25%">Fee Deposited</th>
                        <th width="25%">Deficient Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">Claimant</td>
                        <td class="text-center">Rs.</td>
                        <td class="text-center">Rs.</td>
                        <td class="text-center">Rs.</td>
                    </tr>
                    <tr>
                        <td class="text-center">Respondent</td>
                        <td class="text-center">Rs.</td>
                        <td class="text-center">Rs.</td>
                        <td class="text-center">Rs.</td>
                    </tr>
                </tbody>
            </table>
            <br />

            <p>
                You are required to deposit your respective Arbitrator’s fee including miscellaneous expenses within --------- days with intimation to DIAC</strong>
            </p>

            <p>
                <strong>Mode of Payment:</strong> Payments can be made either by Pay Order/Demand Draft/Cheque drawn in favour of the “below mentioned account numbers”, payable at New Delhi along with a covering letter containing details of the case or pay through RTGS / NEFT, Account No. : <?= DEPARTMENT_ACC_NO ?>, IFSC Code: <?= DEPARTMENT_IFSC ?> [<?= DEPARTMENT_BANK_NAME ?>], MICR Code: <?= DEPARTMENT_MICR_CODE ?>, Branch: <?= DEPARTMENT_BANK_BRANCH ?> with intimation to DIAC
            </p>

            <p>
                <strong>
                    <i>
                        Note: Given the situation caused by COVID-19 pandemic, payments through cheques and Demand Drafts are currently suspended till further orders. The payments are being accepted only through online remittances to the above mentioned Bank Account of DIAC.
                    </i>
                </strong>
            </p>

            <p>
                <strong><q>DIAC deducts TDS while disbursing payment (s) to the Ld. Arbitrator (s), so parties are not to deduct any TDS while making payment (s) of Arbitral Fees/ Administrative Costs to DIAC.</q></strong>
            </p>

            <br /><br />
            <p>
                <strong>(------------------) [D. J. S.]</strong>
            </p>
            <p>
                ADDL. CO-ORDINATOR/DY. REGISTRAR
            </p>
        </div>
    </div>
</div>