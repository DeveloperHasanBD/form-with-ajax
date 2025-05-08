<form action="" id="registerForm">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Navn">
                                <span class="text-danger error error_name"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="date_of_birth" placeholder="Fødselsdato" id="regDatePicker">
                                <input type="date" id="dateInputHidden2" style="display:none;">
                                <span class="text-danger error error_date_of_birth"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="address" placeholder="Adresse">
                                <span class="text-danger error error_address"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="post_code" placeholder="Postnr">
                                <span class="text-danger error error_post_code"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="city" placeholder="By">
                                <span class="text-danger error error_city"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="telephone" placeholder="Tlf">
                                <span class="text-danger error error_telephone"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="email" placeholder="Email">
                                <span class="text-danger error error_email"></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" placeholder="adgangskode">
                                <span class="text-danger error error_password"></span>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="insert_family">
                                    <div class="insert_family_single" id="insert_family_single_1">
                                        <div class="family_single_left">
                                            <div class="form-group">
                                                <div class="dashboard-form-label">Familiemedlem</div>
                                                <input type="text" required name="fm_member_name[]">
                                            </div>
                                        </div>
                                        <div class="family_single_mid">
                                            <div class="form-group">
                                                <div class="dashboard-form-label">Fødselsdato</div>
                                                <input type="date" required name="fm_date_of_birth[]" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="family_single_right">
                                            <div class="member_action_btns">
                                                <a href="" class="btn btn-danger remove_member" row="1">X</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="insert_fmember_add_more_btn ">
                                    <a href="" class="btn btn-primary add_new_member">Tilføj nyt medlem</a>
                                </div>
                                <div class="text-center"><span class="text-danger error_members error"></span></div>
                            </div>
                            <button class="reg-submit-btn" type="submit" id="reg_form_btn">Bliv medlem</button>
                        <div class="success_user_reg d-none">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Din registrering er gennemført med succes! Tjek venligst din e-mail.</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                      </form>
