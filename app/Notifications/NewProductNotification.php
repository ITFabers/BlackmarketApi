<?php

  use App\Notifications\NewProductNotification;

  public function handle(NewProductCreated $event)
  {
      $adminsWithType1 = Admin::where('admin_type', 1)->get();
      Notification::send($adminsWithType1, new NewProductNotification($event->product));
  }

 ?>
