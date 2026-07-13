@extends('layouts.admin')

@section('content')

            <div class="content-area">
                <div class="mr-breadcrumb">
                    <div class="row">
                      <div class="col-lg-12">
                          <h4 class="heading">{{ __('Add Role') }} <a class="add-btn" href="{{route('admin.role.index')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                          <ul class="links">
                            <li>
                              <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                            </li>
                            <li>
                              <a href="{{ route('admin.role.index') }}">{{ __('Manage Roles') }}</a>
                            </li>
                            <li>
                              <a href="{{ route('admin.role.create') }}">{{ __('Add Role') }}</a>
                            </li>
                          </ul>
                      </div>
                    </div>
                  </div>
              <div class="add-product-content">
                @include('includes.admin.form-error')
                @include('includes.admin.form-success')
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                          <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                      <form id="geniusform" action="{{route('admin.role.update',$data->id)}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
            
                        <div class="row">
                          <div class="col-lg-2">
                            <div class="left-area">
                                <h4 class="heading">{{ __("Name") }} *</h4>
                                <p class="sub-heading">EN</p>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <input type="text" class="input-field" name="name" placeholder="{{ __('Name EN') }}" value="{{$data->name}}">
                          </div>
						  
						  <div class="col-lg-2">
                            <div class="left-area">
                                <h4 class="heading">{{ __("Name") }} *</h4>
                                <p class="sub-heading">BN</p>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <input type="text" class="input-field" name="name_bn" placeholder="{{ __('Name BN') }}" value="{{$data->name_bn}}">
                          </div>
                        </div>

                        <hr>
                        <h5 class="text-center">{{ __('Permissions') }}</h5>
                        <hr>

                        <div class="row justify-content-center">
                          <div class="col-lg-4 d-flex justify-content-between">
                            <label class="control-label">{{ __('Menu Builder') }} *</label>
                            <label class="switch">
                              <input type="checkbox" name="section[]" value="menu_builder" {{is_array($values) && in_array('menu_builder',$values) ? 'checked': ''}}>
                              <span class="slider round"></span>
                            </label>
                          </div>
                          <div class="col-lg-2"></div>
                          <div class="col-lg-4 d-flex justify-content-between">
                            
                          </div>
                      </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Pages') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="pages" {{is_array($values) && in_array('pages',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Categories') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="categories" {{is_array($values) && in_array('categories',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                        </div>


                        <div class="row justify-content-center">
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Add News') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="add_post" {{is_array($values) && in_array('add_post',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Add Gallery') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="add_gallery" {{is_array($values) && in_array('add_gallery',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('News') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="posts" {{is_array($values) && in_array('posts',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Schedule Post') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="schedule_post" {{is_array($values) && in_array('schedule_post',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Drafts') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="drafts" {{is_array($values) && in_array('drafts',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Rss Feeds') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="rss_feeds" {{is_array($values) && in_array('rss_feeds',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Polls') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="polls" {{is_array($values) && in_array('polls',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Widgets') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="widgets" {{is_array($values) && in_array('widgets',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                          <div class="col-lg-4 d-flex justify-content-between">
                            <label class="control-label">{{ __('Create Ads') }} *</label>
                            <label class="switch">
                              <input type="checkbox" name="section[]" value="create_ads" {{is_array($values) && in_array('create_ads',$values) ? 'checked': ''}}>
                              <span class="slider round"></span>
                            </label>
                          </div>
                          <div class="col-lg-2"></div>
                          <div class="col-lg-4 d-flex justify-content-between">
                            <label class="control-label">{{ __('NewsLetter') }} *</label>
                            <label class="switch">
                              <input type="checkbox" name="section[]" value="newsLetter" {{is_array($values) && in_array('newsLetter',$values) ? 'checked': ''}}>
                              <span class="slider round"></span>
                            </label>
                          </div>
                      </div>

                      <div class="row justify-content-center">
                        <div class="col-lg-4 d-flex justify-content-between">
                          <label class="control-label">{{ __('Contact Messages') }} *</label>
                          <label class="switch">
                            <input type="checkbox" name="section[]" value="contact_messages" {{is_array($values) && in_array('contact_messages',$values) ? 'checked': ''}}>
                            <span class="slider round"></span>
                          </label>
                        </div>
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4 d-flex justify-content-between">
                          <label class="control-label">{{ __('Languages') }} *</label>
                          <label class="switch">
                            <input type="checkbox" name="section[]" value="languages" {{is_array($values) && in_array('languages',$values) ? 'checked': ''}}>
                            <span class="slider round"></span>
                          </label>
                        </div>
                    </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('General Settings') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="general_settings" {{is_array($values) && in_array('general_settings',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Social Settings') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="social_settings" {{is_array($values) && in_array('social_settings',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-4 d-flex justify-content-between">
                                <label class="control-label">{{ __('SEO Tools') }} *</label>
                                <label class="switch">
                                  <input type="checkbox" name="section[]" value="seo_tools" {{is_array($values) && in_array('seo_tools',$values) ? 'checked': ''}}>
                                  <span class="slider round"></span>
                                </label>
                              </div>

                            <div class="col-lg-2"></div>
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Email Settings') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="emails_settings" {{is_array($values) && in_array('emails_settings',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-4 d-flex justify-content-between">
                                <label class="control-label">{{ __('Role Management') }} *</label>
                                <label class="switch">
                                  <input type="checkbox" name="section[]" value="role_management" {{is_array($values) && in_array('role_management',$values) ? 'checked': ''}}>
                                  <span class="slider round"></span>
                                </label>
                              </div>

                            <div class="col-lg-2"></div>
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Font Option') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="font_option" {{is_array($values) && in_array('font_option',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('User Management') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="user_management" {{is_array($values) && in_array('user_management',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4 d-flex justify-content-between">
                                <label class="control-label">{{ __('Cache Management') }} *</label>
                                <label class="switch">
                                  <input type="checkbox" name="section[]" value="cache_management" {{is_array($values) && in_array('cache_management',$values) ? 'checked': ''}}>
                                  <span class="slider round"></span>
                                </label>
                              </div>
                        </div>

                        <div class="row justify-content-center">
                          <div class="col-lg-4 d-flex justify-content-between">
                            <label class="control-label">{{ __('Administration Management') }} *</label>
                            <label class="switch">
                              <input type="checkbox" name="section[]" value="administration_management" {{is_array($values) && in_array('administration_management',$values) ? 'checked': ''}}>
                              <span class="slider round"></span>
                            </label>
                          </div>
                          <div class="col-lg-2"></div>
							<div class="col-lg-4 d-flex justify-content-between">
                                <label class="control-label">{{ __('Reporter Approve') }} *</label>
                                <label class="switch">
                                  <input type="checkbox" name="section[]" value="reporter_approve" {{is_array($values) && in_array('reporter_approve',$values) ? 'checked': ''}}>
                                  <span class="slider round"></span>
                                </label>
                              </div>
                       </div>
					   
					   <div class="row justify-content-center">
                          <div class="col-lg-4 d-flex justify-content-between">
                            <label class="control-label">{{ __('News Approve') }} *</label>
                            <label class="switch">
                              <input type="checkbox" name="section[]" value="news_approve" {{is_array($values) && in_array('news_approve',$values) ? 'checked': ''}}>
                              <span class="slider round"></span>
                            </label>
                          </div>
                          <div class="col-lg-2"></div>
						  <div class="col-lg-4 d-flex justify-content-between">
                              <label class="control-label">{{ __('Transactions') }} *</label>
                              <label class="switch">
                                <input type="checkbox" name="section[]" value="transaction" {{is_array($values) && in_array('transaction',$values) ? 'checked': ''}}>
                                <span class="slider round"></span>
                              </label>
                            </div>
							
                       </div>

                        <div class="row justify-content-center">
                          <div class="col-lg-4 d-flex justify-content-between">
                            <label class="control-label">{{ __('Product Orders') }} *</label>
                            <label class="switch">
                              <input type="checkbox" name="section[]" value="product_orders" {{is_array($values) && in_array('product_orders',$values) ? 'checked': ''}}>
                              <span class="slider round"></span>
                            </label>
                          </div>
                          <div class="col-lg-2"></div>
                          <div class="col-lg-4 d-flex justify-content-between">
                            <label class="control-label">{{ __('Rashifall') }} *</label>
                            <label class="switch">
                              <input type="checkbox" name="section[]" value="rashifall" {{is_array($values) && in_array('rashifall',$values) ? 'checked': ''}}>
                              <span class="slider round"></span>
                            </label>
                          </div>
                       </div>


                        <div class="row">
                          <div class="col-lg-5">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit">{{ __('Update Role') }}</button>
                          </div>
                        </div>
                      </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

@endsection