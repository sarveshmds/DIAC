<style>
    * {
        font-size: 15px;
    }

    p {
        margin-bottom: 10px;
    }
</style>
<div class="content-wrapper">
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
                        <td width="50%" valign="top">
                            <?php $count = 'a'; ?>
                            <?php foreach ($parties as $key => $party) : ?>
                                <?php if ($party['type'] == 'claimant') : ?>
                                    <p><?= $count++ . ') ' . $party['name'] ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
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
                Respected Sir/Madam,
            </p>
            <p>
                The above mentioned matter has been referred to Delhi International Arbitration Centre (DIAC) for adjudication in terms of the DIAC Rules vide order dated ____________ passed by the Hon’ble High Court of Delhi in Arb. _______________. Your goodself has been appointed as Sole Arbitrator by the Hon’ble High Court in the above said matter.
            </p>
            <p>
                Please <strong>"find attached"</strong> herewith the format of consent/declaration. You are requested to kindly send a Declaration of Acceptance and Statement of Independence as per Schedule V and VI of the Delhi International Arbitration Centre (Arbitration Proceedings) Rules.
            </p>

            <p>
                Furthermore, please find attached rules approved by the Arbitration Committee for the release of Arbitrator's Fees in case of certain eventualities (including settlement), stage-wise.
            </p>

            <br /><br />
            <p>
                <strong>(------------------) [D. J. S.]</strong>
            </p>
            <p>
                ADDL. CO-ORDINATOR/DY. REGISTRAR
            </p>
            <br /><br />
            <p>
                <strong>
                    <u>Encl: As above</u>
                </strong>
            </p>
        </div>
    </div>
</div>