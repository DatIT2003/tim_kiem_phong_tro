<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('limit_description', function ($string) {
                $string = strip_tags($string);
                if (strlen($string) > 150) {
                    $stringCut = substr($string, 0, 150);
                    $endPoint = strrpos($stringCut, ' ');
                    $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                    $string .= '...';
                }
                return $string;
            });

            $view->with('time_elapsed_string', function ($datetime, $full = false) {
                $now = new \DateTime;
                $ago = new \DateTime($datetime);
                $diff = $now->diff($ago);
                $diff->w = floor($diff->d / 7);
                $diff->d -= $diff->w * 7;

                $string = [
                    'y' => 'năm',
                    'm' => 'tháng',
                    'w' => 'tuần',
                    'd' => 'ngày',
                    'h' => 'giờ',
                    'i' => 'phút',
                    's' => 'giây',
                ];
                
                foreach ($string as $k => &$v) {
                    if ($diff->$k) {
                        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
                    } else {
                        unset($string[$k]);
                    }
                }

                if (!$full) $string = array_slice($string, 0, 1);
                return $string ? implode(', ', $string) . ' trước' : 'Vừa xong';
            });
        });
    
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
