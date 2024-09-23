<div>
    <div style="width: 50%; float: left;">
        <?php foreach ($parties as $key => $party) : ?>
            <?php if ($party['type'] == 'claimant') : ?>
                <div <?= ($key > 1) ? 'style = "margin-top: 6px;"' : '' ?>>
                    <p style="margin-bottom: 1px; font-weight: bold;"><?= $party['name'] ?></p>
                    <?php if (isset($party['perm_address_1']) && !empty($party['perm_address_1'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= $party['perm_address_1'] ?>
                        </p>
                    <?php endif; ?>
                    <?php if ((isset($party['perm_address_2']) && $party['perm_address_2'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= $party['perm_address_2'] ?>
                        </p>
                    <?php endif; ?>
                    <?php if ((isset($party['perm_city']) && $party['perm_city'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= $party['perm_city'] ?>
                        </p>
                    <?php endif; ?>
                    <?php if (isset($party['email']) && !empty($party['email'])) : ?>
                        <p style="margin-bottom: 1px;"><?= $party['email'] ?></p>
                    <?php endif; ?>
                    <?php if (isset($party['contact']) && !empty($party['contact'])) : ?>
                        <p style="margin-bottom: 1px;"><?= $party['contact'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div style="width:40%; float: right; vertical-align: bottom;">
        <strong>...Claimant</strong>
    </div>
</div>

<?php if (count($claimant_counsels) > 0) : ?>
    <div style="margin-bottom:0px;clear:both;">
        <div style="width: 50%; float: left;">
            <?php foreach ($claimant_counsels as $key => $counsel) : ?>
                <div <?= ($key > 1) ? 'style = "margin-top: 6px;"' : '' ?>>
                    <p style="margin-bottom: 1px; font-weight: bold;"><?= strtoupper($counsel['name']) ?></p>
                    <?php if (isset($counsel['perm_address_1']) && !empty($counsel['perm_address_1'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= strtoupper($counsel['perm_address_1']) ?>
                        </p>
                    <?php endif; ?>
                    <?php if ((isset($counsel['perm_address_2']) && $counsel['perm_address_2'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= strtoupper($counsel['perm_address_2']) ?>
                        </p>
                    <?php endif; ?>

                    <?php if ((isset($counsel['email']) && $counsel['email'])) : ?>
                        <p style="margin-bottom: 1px;"><?= strtoupper($counsel['email']) ?></p>
                    <?php endif; ?>

                    <?php if ((isset($counsel['phone_number']) && $counsel['phone_number'])) : ?>
                        <p style="margin-bottom: 1px;"><?= $counsel['phone_number'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="width:40%; float: right; vertical-align: bottom;">
            <strong>...Counsel</strong>
        </div>
    </div>
<?php endif; ?>

<div style="clear:both; margin: 15px 0px 4px 0px;">
    <strong>Vs.</strong>
</div>

<div>
    <div style="width: 50%; float: left;">
        <?php foreach ($parties as $key => $party) : ?>
            <?php if ($party['type'] == 'respondant') : ?>
                <div>
                    <p style="margin-bottom: 1px; font-weight: bold;"><?= $party['name'] ?></p>
                    <?php if ((isset($party['perm_address_1']) && $party['perm_address_1'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= $party['perm_address_1'] ?>
                        </p>
                    <?php endif; ?>
                    <?php if ((isset($party['perm_address_2']) && $party['perm_address_2'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= $party['perm_address_2'] ?>
                        </p>
                    <?php endif; ?>
                    <?php if ((isset($party['perm_city']) && $party['perm_city'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= $party['perm_city'] ?>
                        </p>
                    <?php endif; ?>
                    <?php if ((isset($party['email']) && $party['email'])) : ?>
                        <p style="margin-bottom: 1px;"><?= $party['email'] ?></p>
                    <?php endif; ?>
                    <?php if ((isset($party['contact']) && $party['contact'])) : ?>
                        <p style="margin-bottom: 1px;"><?= $party['contact'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div style="width:40%; float: right; vertical-align: bottom;">
        <strong>...Respondent</strong>
    </div>
</div>

<?php if (count($respondent_counsels) > 0) : ?>
    <div style="margin-bottom:0px;clear:both;">
        <div style="width: 50%; float: left;">
            <?php foreach ($respondent_counsels as $key => $counsel) : ?>
                <div <?= ($key > 1) ? 'style = "margin-top: 6px;"' : '' ?>>
                    <p style="margin-bottom: 1px; font-weight: bold;"><?= strtoupper($counsel['name']) ?></p>
                    <?php if (isset($counsel['perm_address_1']) && !empty($counsel['perm_address_1'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= strtoupper($counsel['perm_address_1']) ?>
                        </p>
                    <?php endif; ?>
                    <?php if ((isset($counsel['perm_address_2']) && $counsel['perm_address_2'])) : ?>
                        <p style="margin-bottom: 1px;">
                            <?= strtoupper($counsel['perm_address_2']) ?>
                        </p>
                    <?php endif; ?>
                    <?php if ((isset($counsel['email']) && $counsel['email'])) : ?>
                        <p style="margin-bottom: 1px;"><?= strtoupper($counsel['email']) ?></p>
                    <?php endif; ?>
                    <?php if ((isset($counsel['phone_number']) && $counsel['phone_number'])) : ?>
                        <p style="margin-bottom: 1px;"><?= $counsel['phone_number'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="width:40%; float: right; vertical-align: bottom;">
            <strong>...Counsel</strong>
        </div>
    </div>
<?php endif; ?>

<div style="clear:both;"></div>