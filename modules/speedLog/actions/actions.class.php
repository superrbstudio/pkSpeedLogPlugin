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
    $this->forever = $foreverQ->execute(array(), Doctrine::HYDRATE_ARRAY);
    $this->forever = $this->forever[0]['a'];
    $this->last24 = $last24Q->execute(array(), Doctrine::HYDRATE_ARRAY);
    $this->last24 = $this->last24[0]['a'];
    $this->lastWeek = $lastWeekQ->execute(array(), Doctrine::HYDRATE_ARRAY);
    $this->lastWeek = $this->lastWeek[0]['a'];
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
