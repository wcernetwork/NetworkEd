<div class="slidedown-modal login-slidedown ng-hide" ng-show="actions.showLogin" @if($errors->register->has()) ng-init="actions.authMode = 'register'" @endif @if($errors->passwordreset->has()) ng-init="actions.authMode = 'reset'" @endif>
	<div class="container" scroll-dropdown-modal window-height="windowHeight" check-scroll-height="actions.authMode">
		@if (Auth::user())
			<h1 class="white">
				Hi, [[ Auth::user()->first_name ]] [[ Auth::user()->last_name ]]!
			</h1>

			<h3 class="white">
				<a href="auth/logout">Logout</a>
			</h3>
		@else
			<br />
			<div class="row" ng-show="actions.authMode == 'login'">

				<div class="col-sm-4 col-xs-12">
					<h2 class="white">Explore, share, and contribute to educational breakthroughs in Wisconsin. Join us by creating an account, and start contributing!</h2>
					<br />
					<br />
				</div>

				<div class="col-sm-1">
				</div>

				<div class="col-sm-3 col-xs-6">
					<h2 class="white capitals">&nbsp;&nbsp;Join Us</h2>
					<br />
					<a href="javascript: void(0)" ng-click="actions.authMode = 'register'"><img ng-src="{{ENV.baseUrl}}assets/images/join_link.png" /></a>
				</div>

				<div class="col-sm-4 col-xs-6">
					<h2 class="white capitals">&nbsp;Sign In</h2>
					<br />

					@if($errors->login->has())
						@if($errors->login->first()!='')
						<p class="center"><?= $errors->login->first() ?></p>
						@endif
					@endif

					<form action="{{ENV.baseUrl + 'auth/login'}}" method="POST">
						<input type="email" name="email" value="<?= Input::old('email'); ?>" placeholder="Email" class="form-control login-input-field" @if (Input::old('email') == '') autofocus @endif>
						<input type="password" name="password" value="" placeholder="Password" class="form-control login-input-field" @if (Input::old('email')) autofocus @endif>
						<label><input type="checkbox" name="remember" value="1"> Remember me</label>
						<button id="sign_in" type="submit" class="pull-right btn submit-btn">Sign In <i class="fa fa-chevron-right"></i></button>
						<input name="_token" type="hidden" value="<?php echo csrf_token(); ?>">
					</form>

					<a href="javascript: void(0)" class="white" ng-click="actions.authMode = 'password'">Forgot your password?</a>
				</div>

			</div>

			<div class="row padding-top" ng-show="actions.authMode == 'password'">

				<div class="col-xs-3">
					<h2 class="white">Reset Your Password</h2>
				</div>

				<div class="col-xs-7">
					<form action="{{ENV.baseUrl + 'password/remind'}}" method="POST">
						<div class="col-xs-7">
							<input class="form-control" placeholder="Email" type="email" name="email">
						</div>
					    <div class="col-xs-5">
						    <input name="_token" type="hidden" value="<?php echo csrf_token(); ?>">
					    	<button type="submit" class="btn submit-btn">Send Reminder <i class="fa fa-chevron-right"></i></button>
					    </div>
					</form>
				</div>

				<div class="col-xs-2">
					<h3 class="center">
						<a href="javascript: void(0)" ng-click="actions.authMode = 'login'" class="white">Back to Login</a>
					</h3>
				</div>
			</div>

			<div class="row padding-top" ng-show="actions.authMode == 'reset'">

				<h2 class="white">Reset Your Password</h2>
				<br />
				
				@if($errors->passwordreset->has())
					@if($errors->passwordreset->first()!='')
					<p class="center"><?= $errors->passwordreset->first() ?></p>
					@endif
				@endif

				<form action="{{ENV.baseUrl + 'password/reset'}}" method="POST">
				    <input type="hidden" name="token" value="[[ Session::get('passwordResetToken') ]]">
				    <div class="col-xs-6">
					    Email: <input class="form-control" type="email" name="email"><br />
					    Password: <input class="form-control" type="password" name="password"><br />
					    Password Confirmation: <input class="form-control" type="password" name="password_confirmation"><br />
					    <button type="submit" class="btn submit-btn">Reset Password <i class="fa fa-chevron-right"></i></button>
				   </div>
				</form>

				<div class="clearfix"> </div>
				<br /><br />
				<h3>
					<a href="javascript: void(0)" ng-click="actions.authMode = 'login'" class="white">Back to Login</a>
				</h3>
			</div>

			<div ng-controller="RegistrationController" ng-show="actions.authMode == 'register'">
				<h1 class="white center">Join the Network!</h1>

				@if($errors->register->has())
					@if($errors->register->first('captcha')!='')
					<p class="center"><?= $errors->register->first('captcha') ?></p>
					@endif
					@if($errors->register->first('email')!='')
					<p class="center"><?= $errors->register->first('email') ?></p>
					@endif
					@if($errors->register->first('password')!='')
					<p class="center"><?= $errors->register->first('password') ?></p>
					@endif
				@endif

				<form action="{{ENV.baseUrl + 'auth/register'}}" method="POST" class="join-form">
					<div class="row-fluid">
						<div class="col-sm-6">
							<div class="login-input-group">
								<div class="col-xs-3 col-no-padding">
									<div class="profile-bg" ng-style="{'background-image':'url('+ENV.baseUrl+'user_photos/'+user_photo+')'}">
										<span class="img-helper"></span>
									</div>
								</div>
								<div class="col-xs-9">
									<div class="login-input-label">
										Select Profile Image
									</div>
									<div class="login-input-input">
										<input type="file" accept="image/*" class="form-control post-upload-control" ng-file-select ng-file-change="uploadUserProfile($files, $event)">
										<input type="hidden" ng-value="user_photo" name="photo" />
									</div>
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="login-input-group row-fluid">
								<div class="col-xs-2 login-input-label">
									Email
								</div>
								<div class="col-xs-10 login-input-input">
									<input type="email" name="email" value="<?= Input::old('email'); ?>" placeholder="Email" autofocus class="form-control" required>
								</div>
								<div class="clearfix"> </div>

								<div class="col-xs-2 login-input-label">
									Password
								</div>
								<div class="col-xs-5 login-input-input">
									<input type="password" name="password" value="" placeholder="Password" class="form-control" required>
								</div>
								<div class="col-xs-5 login-input-input">
									<input type="password" name="password_confirmation" value="" placeholder="Confirm Password" class="form-control" required>
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="login-input-group row-fluid">
								<div class="col-xs-2 login-input-label">
									Name
								</div>
								<div class="col-xs-5 login-input-input">
									<input type="text" name="first_name" value="<?= Input::old('first_name'); ?>" placeholder="First Name" class="form-control" required>
								</div>
								<div class="col-xs-5 login-input-input">
									<input type="text" name="last_name" value="<?= Input::old('last_name'); ?>" placeholder="Last Name" class="form-control" required>
								</div>
								<div class="clearfix"> </div>
							</div>

							<div class="login-input-group row-fluid">
								<div class="col-xs-2 login-input-label">
									Location
								</div>
								<div class="col-xs-4 login-input-input">
									<input type="text" name="city" value="<?= Input::old('city'); ?>" placeholder="City" class="form-control" required>
								</div>
								<div class="col-xs-3 login-input-input">
									<input type="text" name="state" value="<?= Input::old('state'); ?>" placeholder="State" class="form-control" required>
								</div>
								<div class="col-xs-3 login-input-input">
									<input type="number" name="zip" value="<?= Input::old('zip'); ?>" placeholder="Zip" class="form-control" required>
								</div>
								<div class="clearfix"> </div>
							</div>

							<div class="login-input-group">
								<div class="col-xs-12 login-input-label">
									Bio
								</div>
								<div class="col-xs-12 login-input-input">
									<textarea class="form-control" name="bio" placeholder="Enter bio here..." required><?= Input::old('bio'); ?></textarea>
								</div>
								<div class="clearfix"> </div>
							</div>

						</div>
						<div class="col-sm-6">
							<div class="login-input-group">
								<div class="login-input-label">
									Role
								</div>
								<div class="row-fluid">
									<div class="col-xs-6" ng-repeat="role in selected_roles">
										<label class="login-input-radio-label">
											<input name="role[]" value="{{role.name}}" type="checkbox" ng-model="role.selected"> {{role.name}}<br />
											<input ng-if="role.name == 'Other' && role.selected" name="role_title_other" type="text" ng-model="role_title_other" class="form-control" />
										</label>
									</div>
									<div class="clearfix"> </div>
								</div>
							</div>

							<div class="login-input-group">
								<div class="col-xs-3 login-input-label">
									Organization
								</div>
								<div class="col-xs-9 login-input-input">
									<input type="text" name="organization" value="<?= Input::old('organization'); ?>" placeholder="Organization" class="form-control" required>
								</div>
								<div class="clearfix"> </div>
							</div>

							<div class="login-input-group">
								<div class="col-xs-12 login-input-label">
									Social Links (Optional. Link to your website, Facebook, LinkedIn, etc)
								</div>
								<div class="col-xs-12 login-input-input">
									<div class="row">
										<div class="col-xs-6">
											<input type="url" name="social_link_1" value="<?= Input::old('social_link_1'); ?>" placeholder="Social Link 1" class="form-control">
										</div>
										<div class="col-xs-6">
											<input type="url" name="social_link_2" value="<?= Input::old('social_link_2'); ?>" placeholder="Social Link 2" class="form-control">
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>

							<div class="login-input-group">
								<div class="col-xs-12 login-input-label">
									Display My Email Address on My Profile
								</div>
								<div class="col-xs-12">
									<input name="share_email" type="checkbox" value="1"> <span class="black">Select to allow others to contact you via email</span>
								</div>
								<div class="clearfix"> </div>
							</div>

							<div class="login-input-group">
								<div class="g-recaptcha" data-sitekey="6LfxwQETAAAAABAVoD8ouM2QusamWWmyw9C-Hjlu"></div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>

					<div class="row-fluid">
						<div class="col-sm-6">
							<h3 class="white pull-left">
								<a href="javascript: void(0)" ng-click="actions.authMode = 'login'">Already registered?</a>
							</h3>
						</div>
						<div class="col-sm-6">
							<div class="pull-right">
								<button class="btn submit-btn" id="register" type="submit">
									<h3 class="white">Start discovering! <i class="fa fa-chevron-right"></i></h3>
								</button>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>

					<input name="_token" type="hidden" value="<?php echo csrf_token(); ?>">
				</form>
			</div>
		@endif
	</div>
</div>