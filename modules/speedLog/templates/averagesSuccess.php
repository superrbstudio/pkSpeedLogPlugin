<h3>Page Speed Measurements</h3>
<p>
  All times in seconds
</p>
<style>
  .speed_log th
  {
    padding: 5px;
    text-align: right;
    border: 2px solid gray;
  }
  .speed_log table
  {
    margin-bottom: 20px;
  }
  .speed_log .edit-speed-monitors
  {
    margin-right: 20px;
  }
</style>
<div class="speed_log">
  <h3>Overall Averages</h3>
  <table class="speed_log_averages">
    <tr>
      <th>Last 24 Hours</th><th>Last 7 Days</th><th>Last Month</th>
      <th>
        <form action="<?php echo url_for('speedLog/averages') ?>" method="POST">
          <?php if ($form->hasErrors()): ?>
            <p>Check your dates.</p>
          <?php endif ?>
          <?php echo $form->renderHiddenFields() ?>
          <?php echo $form['range']->render() ?>
          <input type="submit" value="Update" />
        </form>
      </th>
    </tr>
    <tr>
      <th><?php echo sprintf("%.02f", $last24) ?></th>
      <th><?php echo sprintf("%.02f", $lastWeek) ?></th>
      <th><?php echo sprintf("%.02f", $lastMonth) ?></th>
      <?php if (isset($lastCustom)): ?>
        <th><?php echo sprintf("%.02f", $lastCustom) ?></th>
      <?php else: ?>
        <th></th>
      <?php endif ?>
    </tr>
    <?php // Results are not practical to consume anymore ?>
    <?php if (0): ?>
      <tr>
        <th><?php echo link_to('CSV', 'speedLog/csv?hours=24') ?></th>
        <th><?php echo link_to('CSV', 'speedLog/csv?hours=' . 24 * 7) ?></th>
        <th><?php echo link_to('CSV', 'speedLog/csv?months=1') ?></th>
      </tr>
    <?php endif ?>
  </table>

  <?php echo link_to('<span class="icon"></span>Edit', 'speed_monitor', array(), array('class' => 'icon a-edit a-ui a-btn big edit-speed-monitors')) ?></h3>
  <h3>Speed Monitors</h3>
  <table class="speed_log_rules">
    <tr>
      <th>Rule</th><th>Metric</th><th>Last 24 Hours</th><th>Last 7 Days</th><th>Last Month</th>
      <?php if (isset($lastCustom)): ?>
        <th><?php echo $fromDate ?> &mdash; <?php echo $toDate ?></th>
      <?php endif ?>
    </tr>
    <?php foreach ($ruleResults as $rule => $ruleResults): ?>
      <tr>
        <th rowspan="2"><?php echo $rule ?></th>
        <th>Average</th>
        <?php foreach ($ruleResults as $periodResult): ?>
          <th><?php echo sprintf("%.02f", $periodResult[0]['a']) ?></th>
        <?php endforeach ?>
      </tr>
      <tr>
        <th>Max</th>
        <?php foreach ($ruleResults as $periodResult): ?>
          <th><?php echo sprintf("%.02f", $periodResult[0]['m']) ?></th>
        <?php endforeach ?>
      </tr>
    <?php endforeach ?>
  </table>
  
  <h3>50 Slowest Pages Today</h3>
  <table class="speed_log_slowest">
    <tr>
      <th>Request</th><th>Time</th><th>Count</th>
    </tr>
    <?php foreach ($slow as $item): ?>
      <tr>
        <th><a href="<?php echo htmlspecialchars($item['request']) ?>"><?php echo htmlspecialchars($item['request']) ?></a></th><th><?php echo $item['a'] ?></th><th><?php echo $item['c'] ?></th>
      </tr>
    <?php endforeach ?>
  </table>
</div>
