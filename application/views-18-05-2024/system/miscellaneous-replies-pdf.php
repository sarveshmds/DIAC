<div class="content-wrapper">
    <div class="content">
        <div class="content-heading text-center">
            <h4 class="content-title">
                Miscellaneous - <?= $miscellaneous['misc_number'] ?>
            </h4>
        </div>

        <div class="content-body">
            <div>
                <p>
                    <strong>Message:</strong> <?= ($miscellaneous['remarks']) ? $miscellaneous['remarks'] : 'No message' ?>
                </p>
                <p>
                    <strong>Submitted By (Name):</strong> <?= ($miscellaneous['name']) ? $miscellaneous['name'] : 'No name provided' ?>
                </p>
                <p>
                    <strong>Person's Phone Number:</strong> <?= ($miscellaneous['phone_number']) ? $miscellaneous['phone_number'] : 'No phone no. provided' ?>
                </p>
            </div>
            <br>
            <table class="table table-bordered table-text-center" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width:7%;">S. No.</th>
                        <th style="width: 50%">Reply</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Date of reply</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $serialNo = 1; ?>
                    <?php foreach ($miscellaneous_replies_list as $mrl) : ?>
                        <tr>
                            <td><?= $serialNo ?></td>
                            <td style="text-align: left;"><?= $mrl['reply'] ?></td>
                            <td><?= $mrl['reply_from_user'] . ' (' . $mrl['reply_from_job_title'] . ')' ?></td>
                            <td><?= $mrl['reply_to_user'] . ' (' . $mrl['reply_to_job_title'] . ')' ?></td>
                            <td><?= formatReadableNumberDate($mrl['created_at']) ?></td>
                        </tr>

                        <?php $serialNo++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>