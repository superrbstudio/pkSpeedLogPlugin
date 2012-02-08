<p>Memory | <?php echo link_to('Speed', 'speedLog/averages') ?></p>
<h3>Requests With Highest Memory Usage In The Past Day</h3>
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
  <table class="speed_log_memory">
    <tr>
      <th>Request</th><th>Memory</th>
    </tr>
    <?php foreach ($results as $item): ?>
      <tr>
        <th><a href="<?php echo htmlspecialchars($item['request']) ?>"><?php echo htmlspecialchars($item['request']) ?></a></th><th><?php echo sprintf('%.02f', $item['memory'] / (1024 * 1024)) . 'mb' ?></th>
      </tr>
    <?php endforeach ?>
  </table>
</div>
