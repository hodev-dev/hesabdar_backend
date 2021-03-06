<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Section::insert([
            //تولید
            [
                'name' => "قیچی",
                'code' => 111110,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 1,
                'users' => 5,
                'produce' => 10046160
            ],
            [
                'name' => 'لوله سازی',
                'code' => 111120,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 1,
                'users' => 29,
                'produce' => 1
            ],
            [
                'name' => 'کشش لوله',
                'code' => 111130,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 1,
                'users' => 11,
                'produce' => 742092
            ],
            [
                'name' => 'گالوانیزه لوله',
                'code' => 111150,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 1,
                'users' => 25,
                'produce' => 1
            ],
            [
                'name' => 'تست و پلیسه گیر',
                'code' => 111160,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 1,
                'users' => 31,
                'produce' => 1
            ],
            [
                'name' => 'تست پنج شاخه',
                'code' => 111161,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 1,
                'users' => 1,
                'produce' => 1
            ],
            [
                'name' => 'رزوه',
                'code' => 111151,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 1,
                'users' => 1,
                'produce' => 1502313
            ],
            [
                'name' => 'سالن سازی',
                'code' => 111200,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 1,
                'users' => 5,
                'produce' => 1
            ],

            // خدمات
            [
                'name' => 'برنامه ریزی تولید',
                'code' => 112110,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 1,
                'produce' => 1
            ],
            [
                'name' => 'کنترل کیفی و ازمایشگاه',
                'code' => 112120,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 10,
                'produce' => 1
            ],
            [
                'name' => 'دفتر فنی',
                'code' => 112310,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 4,
                'produce' => 1
            ],
            [
                'name' => 'ماشین سازی',
                'code' => 112320,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 4,
                'produce' => 1
            ],
            [
                'name' => 'تعمیرات برق',
                'code' => 112330,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 16,
                'produce' => 1
            ],
            [
                'name' => 'تعمیرات وسایل نقلیه',
                'code' => 112340,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 16,
                'produce' => 1
            ],
            [
                'name' => 'تعمیرات ساختمانی',
                'code' => 112350,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 3,
                'produce' => 1
            ],
            [
                'name' => 'تعمیرات تاسیساتی',
                'code' => 112360,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 3,
                'produce' => 1
            ],
            [
                'name' => 'تعمیرات خطوط',
                'code' => 112370,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 11,
                'produce' => 1
            ],
            [
                'name' => 'خدمات عمومی رفاه',
                'code' => 112410,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 4,
                'produce' => 1
            ],
            [
                'name' => 'رستوران',
                'code' => 112420,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 3,
                'produce' => 1
            ],
            [
                'name' => 'ترابری',
                'code' => 112430,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 3,
                'produce' => 1
            ],
            [
                'name' => 'بهداشت و درمان',
                'code' => 112440,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 3,
                'produce' => 1
            ],
            [
                'name' => 'ایمنی',
                'code' => 112450,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 1,
                'produce' => 1
            ],
            [
                'name' => 'انبارها',
                'code' => 112710,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 32,
                'produce' => 1
            ],
            [
                'name' => 'انبار قطعات یدکی',
                'code' => 112711,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 2,
                'produce' => 1
            ],
            [
                'name' => 'انبار میانی',
                'code' => 112712,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 7,
                'produce' => 1
            ],
            [
                'name' => 'بهداشت و درمان',
                'code' => 112440,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 1,
                'produce' => 1
            ],
            [
                'name' => 'دفتر فنی',
                'code' => 112120,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 2,
                'users' => 1,
                'produce' => 1
            ],

            // اداری خدمات و فروش
            [
                'name' => 'مدریت',
                'code' => 113210,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 3,
                'users' => 3,
                'produce' => 1
            ],
            [
                'name' => 'امور مالی',
                'code' => 113610,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 3,
                'users' => 3,
                'produce' => 1
            ],
            [
                'name' => 'مخابرات',
                'code' => 113430,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 3,
                'users' => 3,
                'produce' => 1
            ],
            [
                'name' => 'کارگزینی',
                'code' => 113410,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 3,
                'users' => 1,
                'produce' => 1
            ],
            [
                'name' => 'انتظامات',
                'code' => 113440,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 3,
                'users' => 17,
                'produce' => 1
            ],
            [
                'name' => 'فروش',
                'code' => 113510,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 3,
                'users' => 2,
                'produce' => 1
            ],
            [
                'name' => 'تدارکات',
                'code' => 113520,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 3,
                'users' => 1,
                'produce' => 1
            ],
            [
                'name' => 'کامپیوتر',
                'code' => 113620,
                'sharable' => 0,
                'tahsimlable_id' => 1,
                'group_id' => 3,
                'users' => 1,
                'produce' => 1
            ],
        ]);
    }
}
