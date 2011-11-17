<?php

class speedLogActions extends sfActions
{
  public function executeAverages(sfWebRequest $request)
  {
    $foreverQ = Doctrine::getTable('SpeedLog')->createQuery('sl')->select('avg(sl.elapsed) as a');
    $last24Q = clone $foreverQ;
    $last24Q->where('sl.created_at >= NOW() - INTERVAL 1 DAY');
    $lastWeekQ = clone $foreverQ;
    $lastWeekQ->where('sl.created_at >= NOW() - INTERVAL 1 WEEK');
    $lastMonthQ = clone $foreverQ;
    $lastMonthQ->where('sl.created_at >= NOW() - INTERVAL 1 MONTH');
    $this->lastMonth = $lastMonthQ->execute(array(), Doctrine::HYDRATE_ARRAY);
    $this->lastMonth = $this->lastMonth[0]['a'];
    $this->last24 = $last24Q->execute(array(), Doctrine::HYDRATE_ARRAY);
    $this->last24 = $this->last24[0]['a'];
    $this->lastWeek = $lastWeekQ->execute(array(), Doctrine::HYDRATE_ARRAY);
    $this->lastWeek = $this->lastWeek[0]['a'];

    $sql = new aMysql();
    $rules = $sql->queryScalar('SELECT rule FROM speed_monitor ORDER BY rule ASC');
    $this->ruleResults = array();
    $this->intervals = array('Last 24 Hours' => 'INTERVAL 1 DAY', 'Last 7 Days' => 'INTERVAL 1 WEEK', 'Last Month' => 'INTERVAL 1 MONTH');
    foreach ($rules as $rule)
    {
      foreach ($this->intervals as $label => $sql)
      {
        $this->ruleResults[$rule][$label] =  Doctrine::getTable('SpeedLog')->createQuery('sl')->select('avg(sl.elapsed) as a, max(sl.elapsed) as m, count(sl.id) as c')->where('sl.request LIKE ? AND sl.created_at >= NOW() - ' . $sql, array(str_replace(array('*', '?'), array('%', '_'), $rule)))->execute(array(), Doctrine::HYDRATE_ARRAY);
      }
    }
    $this->slow = Doctrine::getTable('SpeedLog')->createQuery('sl')->select('sl.request as request, avg(sl.elapsed) as a, count(sl.id) as c')->where('sl.created_at >= NOW() - INTERVAL 1 DAY')->groupBy('sl.request')->orderBy('a desc')->limit(50)->execute(array(), Doctrine::HYDRATE_ARRAY);
  }
  
  public function executeCsv(sfWebRequest $request)
  {
    $q = Doctrine::getTable('SpeedLog')->createQuery('sl')->select('sl.created_at as created_at, sl.elapsed as elapsed, sl.request as request')->orderBy('sl.created_at');
    $hours = $request->getParameter('hours');
    if ($hours)
    {
      $q->where('sl.created_at >= NOW() - INTERVAL ' . (int) $hours . ' HOUR');
    }
    $months = $request->getParameter('months');
    if ($months)
    {
      $q->where('sl.created_at >= NOW() - INTERVAL ' . (int) $months . ' MONTH');
    }
    $data = $q->execute(array(), Doctrine::HYDRATE_ARRAY);
    header("Content-type: text/csv");
    $out = fopen('php://output', 'w');
    fputcsv($out, array('Time', 'Request', 'Elapsed'));
    foreach ($data as $row)
    {
      unset($row[0]);
      unset($row[1]);
      unset($row[2]);
      unset($row[3]);
      unset($row['id']);
      fputcsv($out, $row);
    }
    exit(0);
  }
}
