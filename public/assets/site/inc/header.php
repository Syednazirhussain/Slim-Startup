		<header id="header" class="transparent-header semi-transparent full-header"  data-sticky-class="not-dark">
			<div id="header-wrap">
				<div class="container clearfix">
					<div id="primary-menu-trigger"><i class="icon-reorder"></i></div>
					<div id="logo">
						<a href="/" class="standard-logo" data-dark-logo="/assets/site/images/logo.png"><img src="/assets/site/images/logo.png" alt="DIDX" oncontextmenu="return false;"></a>
						<a href="/" class="retina-logo" data-dark-logo="/assets/site/images/logo@2x.png"><img src="/assets/site/images/logo@2x.png" alt="DIDX" oncontextmenu="return false;"></a>
					</div>

					<nav id="primary-menu">
						<ul>
							<li class="<?php if($title=="Home"){echo"current";} ?>"><a href="/"><div>Home</div></a></li>
							<li class="<?php if($title=="About Us"){echo"current";} ?>"><a href="/aboutus"><div>About Us</div></a></li>
							<li><a href="#"><div>Buy &amp; Sell</div></a>
								<ul>
									<li><a href="#"><div>Buy DID</div></a></li>
									<li><a href="#"><div>Sell DID</div></a></li>
									<li><a href="#"><div>Rates</div></a></li>
								</ul>
							</li>
							<li class="<?php if($title=="Features of DIDx"){echo"current";} ?>"><a href="/features"><div>Features</div></a></li>
							<li class="<?php if($title=="Worldwide DID Numbers"){echo"current";} ?>"><a href="/dids"><div>Coverage</div></a></li>
							<li><a href="#"><div>Interop</div></a></li>
							<li class="<?php if($title=="Contact Us"){echo"current";} ?>"><a href="/contactus"><div>Contact Us</div></a></li>
						</ul>

						<ul>
							<li class="current"><a href="#SignIn" data-lightbox="inline"><div>Sign In</div></a></li>
						</ul>
					</nav>
				</div>
			</div>
		</header>