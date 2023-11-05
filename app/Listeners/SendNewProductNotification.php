<?php

use App\Notifications\NewProductNotification;

public function handle(NewProductCreated $event)
{
  $adminsWithType1 = Admin::where('admin_type', 1)->get();

 foreach ($adminsWithType1 as $admin) {
     $notification = new Notification([
         'user_id' => $admin->id,
         'message' => 'Created new product',
         'link' => route('product.show', $event->product->id),
     ]);
     $notification->save();
 }
}
