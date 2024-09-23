<div class="">
    <div class="content">
        <div class="content-heading text-center">
            <h4 class="content-title">
                <?= DEPARTMENT_NAME ?>
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
                The above mentioned matter has been referred to Delhi International Arbitration Centre for adjudication in terms of the DIAC Rules vide order dated ____________ passed by ____________.
            </p>
            <p>
                A. To proceed further, you are required to file a Statement of Claim in accordance with Rule 4 of DIAC (Arbitration Proceedings) Rules, 2018 (hereinafter called “the SOC”). Detailed rules of DIAC are available on www.dacdelhi.org.
            </p>

            <p>
                The statement of claim and the documents (if accompanied therewith) should be in [quadruplicate/duplicate] along with one soft copy and <u>should be accompanied with proof of service to the opposite party(s).</u> All copies must be duly signed. Entire filing is to be on A-4 size paper and contents should be typed on both sides of the paper.
            </p>
            <p>
                Note: Given the situation caused by COVID-19 pandemic, physical filing at DIAC are currently suspended till further orders. The filings are being accepted only through E-mails.
            </p>
            <p>
                <strong>
                    <u>
                        B. Administrative Costs, Arbitrator’s Fees & Miscellaneous expenses.
                        As notified by the Arbitration Committee of High Court of Delhi, the revised Administrative Charges (w.e.f 17.11.2020), are as under :-
                    </u>
                </strong>
            </p>
            <br />
            <table class="table w-100 table-bordered" cellspacing="0">
                <thead>
                    <tr>
                        <th>Srl. No.</th>
                        <th width="40%">Sum of amount of claim/counter claim (in Rs.)</th>
                        <th width="40%">Total Misc./Administrative Expenses (in Rs.) each Party</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">i)</td>
                        <td>Upto 20,00,000</td>
                        <td>10,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">ii)</td>
                        <td>20,00,001 to 1,00,00,000</td>
                        <td>20,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">iii)</td>
                        <td>1,00,000,01 to 5,00,00,000</td>
                        <td>40,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">iv)</td>
                        <td>5,00,00,001 and above</td>
                        <td>50,000</td>
                    </tr>
                </tbody>
            </table>
            <br />

            <p>
                Your attention is invited to Schedule B of DIAC (Administrative Costs & Arbitrator’s Fees ) Rules, 2018. <strong><u>You are requested to compute the fees as per your tentative claim and deposit 50% of the same alongwith administrative cost.</u></strong>
            </p>

            <p>
                <strong>Mode of Payment:</strong> Payments can be made either by Pay Order/Demand Draft/Cheque drawn in favour of the “below mentioned account numbers”, payable at New Delhi along with a covering letter containing details of the case or pay through RTGS / NEFT, Account No. : <?= DEPARTMENT_ACC_NO ?>, IFSC Code: <?= DEPARTMENT_IFSC ?> [<?= DEPARTMENT_BANK_NAME ?>], MICR Code: <?= DEPARTMENT_MICR_CODE ?>, Branch: <?= DEPARTMENT_BANK_BRANCH ?> with intimation to DIAC
            </p>

            <p>
                <strong>
                    Note: Given the situation caused by COVID-19 pandemic, payments through cheques and Demand Drafts are currently suspended till further orders. The payments are being accepted only through online remittances to the above mentioned Bank Account of DIAC.
                </strong>
            </p>
            <p>
                <strong>
                    For appointment of arbitrator: -
                </strong>
            </p>
            <p>
                <u>You may suggest five names of Arbitrators within 21 days for appointment of arbitrator</u> under the rules of the Centre or arrive at consensus on one common of arbitrator, failing which, Centre will appoint the Arbitrator on its own, under the rules. The panel of Arbitrators maintained by the Centre is available at <u>our website: <?= DEPARTMENT_WEBSITE ?></u>.
            </p>

            <p>
                The fees, initially deposited, would be provisional. After filing of claim and reply/counter claim, DIAC would re-assess the fees. In case of any deficiency, the same will have to be deposited as and when demanded. Each claim (including interest, if any) should be properly valued and quantified. Where the amount of Claim or the Counter Claim is not quantifiable/ cannot be valued in monetary terms, Rule 34 of DIAC (Arbitration Proceedings) Rules, 2018, would apply.
            </p>

            <p>
                Any communication made to the Counsel shall be deemed to be made to the concerned party.
            </p>

            <p>
                All communications to DIAC should be addressed to: “The Co-ordinator, Delhi International Arbitration Centre (DIAC) Shershah Road, New Delhi- 110503”. Our e-mail id is <?= DEPARTMENT_COMMON_EMAIL ?>
            </p>
            <br />
            <p>
                Email for Filing : <?= DEPARTMENT_EFILING_EMAIL ?>
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