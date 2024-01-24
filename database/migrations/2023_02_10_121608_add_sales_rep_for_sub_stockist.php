<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class AddSalesRepForSubStockist extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      //   sles with SR0306 = id = 641  
      User::whereIn('id', [521, 522, 523, 524, 525, 526, 527, 528, 529, 530, 531, 532, 533, 534])->update([
         'assign_to_sales_rep' => 641,
      ]);

      //   sles with SR0305=  id not found

      // User::whereIn('id',[535,536,537,538,539,540,541,542,543,544,545,546,547,548,549,550,551,552,553,554,555,556,557,558,559,560, 611,611,612,613,614,615,616])->update([
      //    'assign_to_sales_rep'=> ,
      // ]);

      //   sles with SR0308 = id =  636 
      User::whereIn('id', [561, 562, 563, 564, 565, 566, 567, 568, 569, 570, 571, 572, 573, 574, 575, 576, 608])->update([
         'assign_to_sales_rep' => 636,
      ]);

      //   sles with SR0309 = id =  637 

      User::whereIn('id', [577, 578, 579, 580, 581, 582, 583, 609,610])->update([
         'assign_to_sales_rep' => 637,
      ]);


      //   sles with SR0304 = id =  627 id 

      User::whereIn('id', [584, 585, 586, 587, 588, 589, 590, 591, 592, 593, 594, 595, 596, 597, 598,607])->update([
         'assign_to_sales_rep' => 627,
      ]);

    //   sles with SR03010 = id =  638 id 
      
      User::whereIn('id', [599, 600, 601, 602, 603, 604, 605, 606])->update([
         'assign_to_sales_rep' => 638,
      ]);

      // sales with SR0307 id not found

      // User::whereIn('id',[617,618,619,620,621,622,623,624,625,626])->update([
      //    'assign_to_sales_rep'=> 638,
      // ]);
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
   }
}
