function club_register()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'club_users';
    $member_table_name = $wpdb->prefix . 'user_family_members';
    // Retrieve form data
    $name = sanitize_text_field($_POST['name']);
    $date_of_birth = sanitize_text_field($_POST['date_of_birth']);
    $address = sanitize_text_field($_POST['address']);
    $post_code = sanitize_text_field($_POST['post_code']);
    $city = sanitize_text_field($_POST['city']);
    $telephone = sanitize_text_field($_POST['telephone']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $hashed_password = wp_hash_password($password);

    $member_names   = $_POST['fm_member_name'] ?? array();
    $member_births  = $_POST['fm_date_of_birth'] ?? array();
    // Filter out empty values
    $filtered_names  = array_filter($member_names, 'strlen');
    $filtered_births = array_filter($member_births, 'strlen');

    if (count($filtered_names) == 0 && count($filtered_births) == 0) {
        wp_send_json_error(['errors' => ['members' => 'Der kræves minimum et familiemedlem.']]);
    }
    // Array to store errors
    $errors = [];

    // Field validation
    if (empty($name)) {
        $errors['name'] = 'Navn er påkrævet.';
    }

    // if (!$date_of_birth) {
    // 	$errors['date_of_birth'] = 'Fødselsdato er påkrævet.'; }


    if (empty($address)) {
        $errors['address'] = 'Address er påkrævet.';
    }
    if (empty($post_code)) {
        $errors['post_code'] = 'Postnr er påkrævet.';
    }
    if (empty($city)) {
        $errors['city'] = 'By er påkrævet.';
    }
    if (empty($telephone)) {
        $errors['telephone'] = 'Tlf er påkrævet.';
    }
    if (empty($email)) {
        $errors['email'] = 'E-mail er påkrævet';
    }

    if (empty($password)) {
        $errors['password'] = 'Adgangskode er påkrævet.';
    } elseif (strlen($password) < 4) {
        $errors['password'] = 'Adgangskode skal være minimum 4 karakter lang';
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        wp_send_json_error(['errors' => $errors]);
    }
    // Check if Unik kode already exists
    $existing_user = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE text_password = %s", $password));

    if ($existing_user) {
        wp_send_json_error(['errors' => ['members' => 'Unik kode allerede registreret!']]);
    }
    // Insert new user
    $inserted = $wpdb->insert($table_name, [
        'name'          => $name,
        'date_of_birth' => $date_of_birth,
        'address'       => $address,
        'post_code'     => $post_code,
        'city'          => $city,
        'email'         => $email,
        'telephone'     => $telephone,
        'password'      => $hashed_password,
        'member_number' => $password,
        'text_password' => $password,
    ]);

    if ($inserted) {


        $member_imp = implode(', ', $member_names);
        // mail info 
        $to_mail = $email;
        $headers = '';
        $headers .= "From: Kutuga Kulturforening <noreply@kutuga.dk> \r\n";
        $subject  = "Derneğimize Kaydınız Alınmıştır";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $msg = '';
        $msg .= 'Sayın: ' . $name . "<br>";
        $msg .= 'Kutuga kulturforening’ne üyelik başvurunuz başarıyla alınmıştır. <br><br>';
        $msg .= 'Navn: ' . $name . "<br>";;
        $msg .= 'Adresse: ' . $address . "<br>";;
        $msg .= 'Post nr: ' . $post_code . "<br>";;
        $msg .= 'By: ' . $city . "<br>";;
        $msg .= 'Tlf: ' . $telephone . "<br>";;
        $msg .= 'Email: ' . $email . "<br>";;
        $msg .= 'Fødselsdato: ' . $date_of_birth . "<br>";;
        $msg .= 'Familiemedlem: ' . $member_imp . "<br><br>";

        $msg .= '<a href="https://kutuga.dk">Kutuga.dk</a> log ind bilgileriniz:<br><br>';

        $msg .= 'Brugernavn: ' . $name . '<br>';
        $msg .= 'Adgangskode: ' . $password . '<br><br>';

        $msg .= 'Dernek faaliyetlerimiz, etkinlik duyuruları ve önemli bilgiler için sizi <a href="https://kutuga.dk">Kutuga.dk</a> üzerinden takip etmeye davet ediyoruz.<br><br>';

        $msg .= 'Eğer herhangi bir sorunuz varsa veya üyelik bilgilerinizde düzeltme yapmanız gerekiyorsa, <a href="https://kutuga.dk">Kutuga.dk</a> adresinden yapabilirsiniz. <br><br><br>';

        $msg .= 'Saygılarımla, <br>';
        $msg .= 'Osman Gøz  <br>';
        $msg .= ' kulturforening<br>';
        $msg .= '🌐 <a href="https://kutuga.dk">Kutuga.dk</a>';

        wp_mail($to_mail, $subject, $msg, $headers, 'kutuga.dk');

        // mail info 


        $user_id = $wpdb->insert_id;

        if ($member_names) {
            $wpdb->query('START TRANSACTION');
            try {
                // Insert new members
                if (!empty($member_names)) {
                    foreach ($member_names as $key => $member_name) {
                        $member_birth = $member_births[$key] ?? '';

                        $inserted = $wpdb->insert($member_table_name, [
                            'user_id'     => $user_id,
                            'member_name' => sanitize_text_field($member_name),
                            'birthday'    => sanitize_text_field($member_birth),
                        ]);

                        if ($inserted === false) {
                            wp_send_json_error(['errors' => 'Error inserting member: ' . $member_name]);
                        }
                    }
                }

                // Commit the transaction
                $wpdb->query('COMMIT');
            } catch (Exception $e) {
                // Rollback the transaction
                $wpdb->query('ROLLBACK');
                wp_send_json_error(['errors' => 'Something wrong!']);
            }
        }

        wp_send_json(['success' => true]);
        // Auto login user

         session_start();
         $_SESSION['club_user_id']   = $user_id;
         $_SESSION['club_user_name'] = $password; // unique code $password

        wp_send_json(['success' => true, 'redirect_url' => home_url('/login')]);
    } else {
        wp_send_json(['success' => false, 'message' => 'Registration failed. Try again!']);
    }
}
