<?php

namespace App\Console\Commands\Stat;

use App\Model\AcitvityChannelDetail;
use App\Model\Activity;
use App\Model\Logs\TaskLog;
use App\Services\Utils\BaiduTj;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StatBaidu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:baidu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '计算pv数';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $url = config('baidutj.url');
            $headers['type'] = config('baidutj.type');
            $headers['username'] = config('baidutj.username');
            $headers['password'] = config('baidutj.password');
            $headers['token'] = config('baidutj.token');
            $body['siteId'] = config('baidutj.siteId');
            $body['method'] = 'overview/getCommonTrackRpt';
            $body['s_time'] = Carbon::now()->subYear(1)->format('Ymd');
            $body['e_time'] = Carbon::now()->format('Ymd');
            $body['gran'] = 'month';
            $body['max_results']   = 0;
            $vData = BaiduTj::getData($url, $headers, $body);
            if(isset($vData['body']['data'][0]['result']['visitPage']['items']) && !empty($vData['body']['data'][0]['result']['visitPage']['items']))
            {
                $result = $vData['body']['data'][0]['result']['visitPage']['items'];

                foreach ($result as $key =>$value)
                {
                    $arr[$value[0]] = (int)$value[1];
                }
            }else{
                throw new \Exception('暂无数据');
            }

            $res = AcitvityChannelDetail::where('is_del',1)->get();

            if(!$res->count()){
               throw new \Exception('当前暂无活动数据');
            }
            foreach ($res as $key=>&$value)
            {
                if(isset($arr[$value->url]))
                {
                    $value->PV = $arr[$value->url];
                    $value->updated_at = Carbon::now();
                    $value->save();
                }
            }

            $all = DB::table('activity_channel_detail')->select(DB::raw('SUM(pv) as total_pv , act_id'))
                ->where('is_del', '=', 1)
                ->groupBy('act_id')
                ->get();

            foreach ($all as $k=>$v){
                Activity::where('id',$v->act_id)->update([
                    'PV'=>$v->total_pv,
                ]);
            }

            TaskLog::insert([
                'task_name'=>$this->signature,
                'result'=>'success',
                'error'=>null,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
        }catch (\Exception $e){
            TaskLog::insert([
                'task_name'=>$this->signature,
                'result'=>'fail',
                'error'=>$e->getMessage(),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
        }

        return true;
    }
}
