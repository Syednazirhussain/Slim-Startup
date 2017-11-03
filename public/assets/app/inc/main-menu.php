
<div class="sidebar clearfix">
  <ul class="sidebar-panel nav">
    <li><a class="<?php if ($title=="Home"){echo"active";}?>" href="/Home"><span class="icon color4"><i class="fa fa-home"></i></span>Home</a></li>

    <li><a href="http://kb.didx.net"><span class="icon color4"><i class="fa fa-life-bouy"></i></span>Help Desk</a></li>

    <li><a class="<?php if ($title=="DID Refunds"){echo"active";}?>" href="/MyRefundClaims"><span class="icon color5"><i class="fa fa-history"></i></span>DID Refunds<span class="label label-danger">0</span></a></li>

    <li><a class="<?php if ($title=="Buy DID"){echo"active";}?>" href="/BuyDID"><span class="icon color4"><i class="fa fa-shopping-cart"></i></span>Buy DIDs</a></li>

    <li><a class="<?php if ($title=="Special Offers"){echo"active";}?>" href="/specialoffers/"><span class="icon color4"><i class="fa fa-bullhorn"></i></span>Special Offer</a></li>

    <li><a class="<?php if ($title=="Buy DIDs in Bulk"){echo"active";}?>" href="/BuyBulkDIDS"><span class="icon color4"><i class="fa fa-archive"></i></span>Bulk Order</a></li>

    <li><a class="<?php if ($title=="Purchased DIDs"){echo"active";}?>" href="/MyPurchasedDIDs"><span class="icon color4"><i class="fa fa-shopping-basket"></i></span>My Purchased DIDs</a></li>

    <li><a class="<?php if ($title=="Tools"){echo"active";}?>" href="/Tools"><span class="icon color4"><i class="fa fa-wrench"></i></span>Tool Box</a></li>

    <li><a class="<?php if ($title=="Request DID"){echo"active";}?>" href="/RequestDID"><span class="icon color4"><i class="fa fa-plus-circle"></i></span>Request DIDs</a></li>

    <li><a class="<?php if ($title=="Requested DIDs"){echo"active";}?>" href="/ViewRequestedDID"><span class="icon color4"><i class="fa fa-list-alt"></i></span>View Requested DIDs</a></li>

    <li><a class="<?php if ($title=="Reports"){echo"active";}?>" href="/Reports"><span class="icon color4"><i class="fa fa-file-text"></i></span>Reports</a></li>

    <li><a href="/SignoutAction"><span class="icon color4"><i class="fa fa-sign-out"></i></span>Signout</a></li>
  </ul>

  <div class="sidebar-plan">
    <form class="form-horizontal">
      <input class="form-control form-control-radius" id="wish" type="text" placeholder="I wish this page would..." title="Type here to make a wish">
    </form>
  </div>
</div>