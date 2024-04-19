<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
1	= Approved
2	= Verified
3	= Revise
4	= Pending
5	= Canceled
6	= Rejected
7	= Draft
*/

function approval_control($request_status_id){

    $action_control = array();

    switch ($request_status_id){
        // Approve
        case 1 :
            $action_control = array(
                "approve"  => false,
                "verify"   => false,
                "revise"   => false,
                "pending"  => false,
                "cancel"   => false,
                "reject"   => false,
                "draft"    => false,
                "edit"     => false
            );
            break;

        // Verify
        case 2 :
            $action_control = array(
                "approve"  => true,
                "verify"   => false,
                "revise"   => false,
                "pending"  => false,
                "cancel"   => false,
                "reject"   => true,
                "draft"    => false,
                "edit"     => false
            );
            break;

        // Revise
        case 3 :
            $action_control = array(
                "approve"  => false,
                "verify"   => false,
                "revise"   => false,
                "pending"  => true,
                "cancel"   => true,
                "reject"   => false,
                "draft"    => false,
                "edit"     => true
            );
            break;

        // Pending
        case 4 :
            $action_control = array(
                "approve"  => true,
                "verify"   => true,
                "revise"   => true,
                "pending"  => false,
                "cancel"   => true,
                "reject"   => true,
                "draft"    => false,
                "edit"     => false
            );
            break;

        // Cancel
        case 5 :
            $action_control = array(
                "approve"  => false,
                "verify"   => false,
                "revise"   => false,
                "pending"  => false,
                "cancel"   => false,
                "reject"   => false,
                "draft"    => false,
                "edit"     => false
            );
            break;

        // Reject
        case 6 :
            $action_control = array(
                "approve"  => false,
                "verify"   => false,
                "revise"   => false,
                "pending"  => false,
                "cancel"   => false,
                "reject"   => false,
                "draft"    => false,
                "edit"     => false
            );
            break;

        // Draft
        case 7 :
            $action_control = array(
                "approve"  => false,
                "verify"   => false,
                "revise"   => false,
                "pending"  => true,
                "cancel"   => true,
                "reject"   => false,
                "draft"    => false,
                "edit"     => true
            );
            break;

        default:
            $action_control = array(
                "approve"  => false,
                "verify"   => false,
                "revise"   => false,
                "pending"  => true,
                "cancel"   => true,
                "reject"   => false,
                "draft"    => false,
                "edit"     => true
            );
            break;

    }

    return $action_control;
}



?>