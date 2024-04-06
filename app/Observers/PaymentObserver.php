<?php
//
//namespace App\Observers;
//
//use App\Models\Payment;
//use App\Notifications\PaymentsNotification;
//use Illuminate\Support\Facades\Notification;
//
//class PaymentObserver
//{
//    /**
//     * Handle the Payment "created" event.
//     */
//    public function created(Payment $payment): void
//    {
//        $user = $payment->user;
//        $paymentAmount = $payment->amount;
//        $dueDate = $payment->due_date;
//        Notification::send($user, new PaymentsNotification($paymentAmount, $dueDate));
//    }
//
//    /**
//     * Handle the Payment "updated" event.
//     */
//    public function updated(Payment $payment)
//    {
//        /* if ($payment->paymentIsDue()) {
//            $user = $payment->user;
//            $amountDue = $payment->amount;
//            Notification::send($user, new PaymentsNotification($amountDue));
//        }*/
//    }
//
//    /**
//     * Handle the Payment "deleted" event.
//     */
//    public function deleted(Payment $payment): void
//    {
//        //
//    }
//
//    /**
//     * Handle the Payment "restored" event.
//     */
//    public function restored(Payment $payment): void
//    {
//        //
//    }
//
//    /**
//     * Handle the Payment "force deleted" event.
//     */
//    public function forceDeleted(Payment $payment): void
//    {
//        //
//    }
//}
