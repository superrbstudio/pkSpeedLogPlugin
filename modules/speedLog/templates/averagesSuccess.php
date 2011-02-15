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
</style>
<div class="speed_log">
  <h3>Averages and Downloads</h3>
  <table class="speed_log_averages">
    <tr>
      <th>Last 24 Hours</th><th>Last 7 Days</th><th>Forever</th>
    </tr>
    <tr>
      <th><?php echo sprintf("%.02f", $last24) ?></th>
      <th><?php echo sprintf("%.02f", $lastWeek) ?></th>
      <th><?php echo sprintf("%.02f", $forever) ?></th>
    </tr>
    <tr>
      <th><?php echo link_to('CSV', 'speedLog/csv?hours=24') ?></th>
      <th><?php echo link_to('CSV', 'speedLog/csv?hours=' . 24 * 7) ?></th>
      <th><?php echo link_to('CSV', 'speedLog/csv') ?></th>
    </tr>
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
