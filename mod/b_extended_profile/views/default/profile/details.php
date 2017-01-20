<?php
/*
 * Author: Bryden Arndt
 * Date: 01/07/2015
 * Purpose: Display the profile details for the user profile in question.
 * Requires: gcconnex-profile.js should already be loaded at this point to handle edit/save/cancel toggles and other ajax requests related to profile sections
 * font-awesome css should be loaded already
 */

$user = elgg_get_page_owner_entity();
$profile_fields = elgg_get_config('profile_fields');

// display the username, title, phone, mobile, email, website, and user type
echo '<div class="panel-heading clearfix"><div class="pull-right clearfix">';
echo '<div class="gcconnex-profile-name">';

//edit button
if ($user->canEdit()) {
    $editAvatar = elgg_get_site_url(). 'avatar/edit/' . $user->username;
    echo '<button type="button" class="btn btn-primary gcconnex-edit-profile" data-toggle="modal" data-target="#editProfile" data-colorbox-opts = \'{"inline":true, "href":"#editProfile", "innerWidth": 800, "maxHeight": "80%"}\'>' . elgg_echo('gcconnex_profile:edit_profile') . ' <span class="wb-inv">' . elgg_echo('profile:contactinfo') . '</span></button>';
    // pop up or modal for the edit profile (name, occupation, title, ... etc)
    echo '<div class="modal" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog dialog-box">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    echo '              <h2>' . elgg_echo('gcconnex_profile:basic:header') . '</h2>';
    echo '          </div>';
    echo '          <div class="panel-body">';
    echo '              <div><a href='.$editAvatar.' class="btn btn-primary">'. elgg_echo('gcconnex_profile:profile:edit_avatar') .'</a></div>';

    // container for css styling, used to group profile content and display them seperately from other fields
    echo '              <div class="basic-profile-standard-field-wrapper col-sm-6 col-xs-12">'; 

    // form that displays the user fields
    $fields = array('Name', 'user_type', 'Federal', 'Provincial', 'Institution', 'University', 'College', 'Job', 'Location', 'Phone', 'Mobile', 'Email', 'Website');

    foreach ($fields as $field) {

        echo '<div class="basic-profile-field-wrapper col-xs-12">';

        // create a label and input box for each field on the basic profile (see $fields above)
        $field = strtolower($field);
        $value = $user->get($field);

        // occupation input
        if (strcmp($field, 'user_type') == 0) {
            
            echo "<label for='{$field}' class='basic-profile-label {$field}-label'>" . elgg_echo("gcconnex_profile:basic:{$field}")."</label>";
            echo '<div class="basic-profile-field user_type-test">';
            echo elgg_view('input/select', array(
                'name' => $field,
                'id' => $field,
                'class' => "gcconnex-basic-{$field}",
                'value' => $value,
                'options_values' => array('federal' => 'Federal Government', 'academic' => 'Academic', 'student' => 'Student', 'provincial' => 'Provincial/Territorial Government'),
            ));


            // jquery for the occupation dropdown - institution
            ?>

            <script>

            $(document).ready(function () {

                var user_occupation_top = $("#user_type").val();
                var user_institution_top = $("#institution").val();
                if (user_occupation_top == 'student' || user_occupation_top == 'academic') {
                    $(".job-label").hide();
                    $("#job").hide();
                    $(".federal-label").hide();
                    $("#federal").hide();
                    $(".provincial").hide();
                    $(".ministry").hide();

                    if(user_institution_top == "university"){
                        $(".college-label").hide();
                        $("#college").hide();
                    } else if (user_institution_top == "college"){
                        $(".university-label").hide();
                        $("#university").hide();
                    }
                } else if (user_occupation_top == 'provincial') {
                    $(".job-label").hide();
                    $("#job").hide();
                    $(".federal-label").hide();
                    $("#federal").hide();

                    $(".institution-label").hide();
                    $("#institution").hide();
                    $(".university-label").hide();
                    $("#university").hide();
                    $(".college-label").hide();
                    $("#college").hide();
                } else {
                    $(".job-label").hide();
                    $("#job").hide();
                    $(".provincial").hide();
                    $(".ministry").hide();

                    $(".institution-label").hide();
                    $("#institution").hide();
                    $(".university-label").hide();
                    $("#university").hide();
                    $(".college-label").hide();
                    $("#college").hide();
                }

                $("#user_type").change(function() {
                    var user_occupation = $(this).val();
                    var user_institution = $("#institution").val();
                    if (user_occupation == "federal") {
                        $(".institution-label").hide();
                        $("#institution").hide();
                        $(".university-label").hide();
                        $("#university").hide();
                        $(".college-label").hide();
                        $("#college").hide();
                        $(".provincial").hide();
                        $(".ministry").hide();

                        $(".job-label").show();
                        $("#job").show();
                        $(".federal-label").show();
                        $("#federal").show();
                    } else if (user_occupation == "provincial") {
                        $(".institution-label").hide();
                        $("#institution").hide();
                        $(".university-label").hide();
                        $("#university").hide();
                        $(".college-label").hide();
                        $("#college").hide();
                        $(".job-label").hide();
                        $("#job").hide();
                        $(".federal-label").hide();
                        $("#federal").hide();

                        $(".provincial").show();
                        $('.ministry').hide();
                        $('.' + province.replace(/\s+/g, '-').toLowerCase()).show();
                        $('#' + province.replace(/\s+/g, '-').toLowerCase()).show();
                    } else if (user_occupation == "student" || user_occupation == "academic") {
                        $(".job-label").hide();
                        $("#job").hide();
                        $(".federal-label").hide();
                        $("#federal").hide();
                        $(".provincial").hide();
                        $(".ministry").hide();

                        $(".institution-label").show();
                        $("#institution").show();
                        if(user_institution == "university"){
                            $(".university-label").show();
                            $("#university").show();
                        } else if (user_institution == "college"){
                            $(".college-label").show();
                            $("#college").show();
                        }
                    }
                });

                $("#institution").change(function() {
                    if($(this).val() == "university"){
                        $(".university-label").show();
                        $("#university").show();

                        $(".college-label").hide();
                        $("#college").hide();
                    } else if($(this).val() == "college"){
                        $(".college-label").show();
                        $("#college").show();

                        $(".university-label").hide();
                        $("#university").hide();
                    }
                });

                $("#provincial").change(function() {
                    var province = $(this).val();
                    $('.ministry').hide();
                    $('.' + province.replace(/\s+/g, '-').toLowerCase()).show();
                    $('#' + province.replace(/\s+/g, '-').toLowerCase()).show();
                });
            });


            </script>

            <?php


        // federal input field
        } else if ($field == 'federal') {

            echo "<label for='{$field}' class='basic-profile-label {$field}-label'>" . elgg_echo("gcconnex_profile:basic:{$field}")."</label>";
            echo '<div class="basic-profile-field department-test">';
            

            /*
            $obj = elgg_get_entities(array(
                'type' => 'object',
                'subtype' => 'dept_list',
                'owner_guid' => 0
            ));

            // english / french departments
            if (get_current_language() == 'en') {
                $departments = $obj[0]->deptsEn;
                $departments = json_decode($departments, true);
                $provinces['pov-alb'] = 'Government of Alberta';
                $provinces['pov-bc'] = 'Government of British Columbia';
                $provinces['pov-man'] = 'Government of Manitoba';
                $provinces['pov-nb'] = 'Government of New Brunswick';
                $provinces['pov-nfl'] = 'Government of Newfoundland and Labrador';
                $provinces['pov-ns'] = 'Government of Nova Scotia';
                $provinces['pov-nwt'] = 'Government of Northwest Territories';
                $provinces['pov-nun'] = 'Government of Nunavut';
                $provinces['pov-ont'] = 'Government of Ontario';
                $provinces['pov-pei'] = 'Government of Prince Edward Island';
                $provinces['pov-que'] = 'Government of Quebec';
                $provinces['pov-sask'] = 'Government of Saskatchewan';
                $provinces['pov-yuk'] = 'Government of Yukon';
                $departments = array_merge($departments,$provinces);
            } else {
                $departments = $obj[0]->deptsFr;
                $departments = json_decode($departments, true);
                $provinces['pov-alb'] = "Gouvernement de l'Alberta";
                $provinces['pov-bc'] = 'Gouvernement de la Colombie-Britannique';
                $provinces['pov-man'] = 'Gouvernement du Manitoba';
                $provinces['pov-nb'] = 'Gouvernement du Nouveau-Brunswick';
                $provinces['pov-nfl'] = 'Gouvernement de Terre-Neuve-et-Labrador';
                $provinces['pov-ns'] = 'Gouvernement de la Nouvelle-Écosse';
                $provinces['pov-nwt'] = 'Gouvernement du Territoires du Nord-Ouest';
                $provinces['pov-nun'] = 'Gouvernement du Nunavut';
                $provinces['pov-ont'] = "Gouvernement de l'Ontario";
                $provinces['pov-pei'] = "Gouvernement de l'Île-du-Prince-Édouard";
                $provinces['pov-que'] = 'Gouvernement du Québec';
                $provinces['pov-sask'] = 'Gouvernement de Saskatchewan';
                $provinces['pov-yuk'] = 'Gouvernement du Yukon';
                $departments = array_merge($departments,$provinces);
            }
            */

            $federal_departments = array();
            if (get_current_language() == 'en'){
                $federal_departments = array("Aboriginal Business Canada" => "Aboriginal Business Canada",
                "Administrative Tribunals Support Service of Canada" => "Administrative Tribunals Support Service of Canada",
                "Agriculture and Agri-Food Canada" => "Agriculture and Agri-Food Canada",
                "Atlantic Canada Opportunities Agency" => "Atlantic Canada Opportunities Agency",
                "Atlantic Pilotage Authority Canada" => "Atlantic Pilotage Authority Canada",
                "Atomic Energy of Canada Limited" => "Atomic Energy of Canada Limited",
                "Auditor General of Canada (Office of the)" => "Auditor General of Canada (Office of the)",
                "Bank of Canada" => "Bank of Canada",
                "Blue Water Bridge Canada" => "Blue Water Bridge Canada",
                "Business Development Bank of Canada" => "Business Development Bank of Canada",
                "Canada Agricultural Review Tribunal" => "Canada Agricultural Review Tribunal",
                "Canada Agriculture and Food Museum" => "Canada Agriculture and Food Museum",
                "Canada Aviation and Space Museum" => "Canada Aviation and Space Museum",
                "Canada Border Services Agency" => "Canada Border Services Agency",
                "Canada Centre for Inland Waters" => "Canada Centre for Inland Waters",
                "Canada Council for the Arts" => "Canada Council for the Arts",
                "Canada Deposit Insurance Corporation" => "Canada Deposit Insurance Corporation",
                "Canada Development Investment Corporation" => "Canada Development Investment Corporation",
                "Canada Economic Development for Quebec Regions" => "Canada Economic Development for Quebec Regions",
                "Canada Employment Insurance Commission" => "Canada Employment Insurance Commission",
                "Canada Firearms Centre" => "Canada Firearms Centre",
                "Canada Gazette" => "Canada Gazette",
                "Canada Industrial Relations Board" => "Canada Industrial Relations Board",
                "Canada Lands Company Limited" => "Canada Lands Company Limited",
                "Canada Mortgage and Housing Corporation" => "Canada Mortgage and Housing Corporation",
                "Canada Pension Plan Investment Board" => "Canada Pension Plan Investment Board",
                "Canada Post" => "Canada Post",
                "Canada Research Chairs" => "Canada Research Chairs",
                "Canada Revenue Agency" => "Canada Revenue Agency",
                "Canada School of Public Service" => "Canada School of Public Service",
                "Canada Science and Technology Museum Corporation" => "Canada Science and Technology Museum Corporation",
                "Canadian Air Transport Security Authority" => "Canadian Air Transport Security Authority",
                "Canadian Army" => "Canadian Army",
                "Canadian Cadet Organizations" => "Canadian Cadet Organizations",
                "Canadian Centre for Occupational Health and Safety" => "Canadian Centre for Occupational Health and Safety",
                "Canadian Coast Guard" => "Canadian Coast Guard",
                "Canadian Commercial Corporation" => "Canadian Commercial Corporation",
                "Canadian Conservation Institute" => "Canadian Conservation Institute",
                "Canadian Cultural Property Export Review Board" => "Canadian Cultural Property Export Review Board",
                "Canadian Dairy Commission" => "Canadian Dairy Commission",
                "Canadian Environmental Assessment Agency" => "Canadian Environmental Assessment Agency",
                "Canadian Food Inspection Agency" => "Canadian Food Inspection Agency",
                "Canadian Forces Housing Agency" => "Canadian Forces Housing Agency",
                "Canadian General Standards Board" => "Canadian General Standards Board",
                "Canadian Grain Commission" => "Canadian Grain Commission",
                "Canadian Heritage" => "Canadian Heritage",
                "Canadian Heritage Information Network" => "Canadian Heritage Information Network",
                "Canadian Human Rights Commission" => "Canadian Human Rights Commission",
                "Canadian Institutes of Health Research" => "Canadian Institutes of Health Research",
                "Canadian Intellectual Property Office" => "Canadian Intellectual Property Office",
                "Canadian Intergovernmental Conference Secretariat" => "Canadian Intergovernmental Conference Secretariat",
                "Canadian International Trade Tribunal" => "Canadian International Trade Tribunal",
                "Canadian Judicial Council" => "Canadian Judicial Council",
                "Canadian Museum for Human Rights" => "Canadian Museum for Human Rights",
                "Canadian Museum of Contemporary Photography" => "Canadian Museum of Contemporary Photography",
                "Canadian Museum of History" => "Canadian Museum of History",
                "Canadian Museum of Immigration at Pier 21" => "Canadian Museum of Immigration at Pier 21",
                "Canadian Museum of Nature" => "Canadian Museum of Nature",
                "Canadian Northern Economic Development Agency" => "Canadian Northern Economic Development Agency",
                "Canadian Nuclear Safety Commission" => "Canadian Nuclear Safety Commission",
                "Canadian Pari-Mutuel Agency" => "Canadian Pari-Mutuel Agency",
                "Canadian Police College" => "Canadian Police College",
                "Canadian Race Relations Foundation" => "Canadian Race Relations Foundation",
                "Canadian Radio-Television and Telecommunications Commission" => "Canadian Radio-Television and Telecommunications Commission",
                "Canadian Security Intelligence Service" => "Canadian Security Intelligence Service",
                "Canadian Space Agency" => "Canadian Space Agency",
                "Canadian Tourism Commission" => "Canadian Tourism Commission",
                "Canadian Trade Commissioner Service" => "Canadian Trade Commissioner Service",
                "Canadian Transportation Agency" => "Canadian Transportation Agency",
                "Canadian War Museum" => "Canadian War Museum",
                "Chief Electoral Officer (Office of the)" => "Chief Electoral Officer (Office of the)",
                "Civilian Review and Complaints Commission for the RCMP" => "Civilian Review and Complaints Commission for the RCMP",
                "Clerk of the Privy Council" => "Clerk of the Privy Council",
                "Commissioner for Federal Judicial Affairs Canada (Office of the)" => "Commissioner for Federal Judicial Affairs Canada (Office of the)",
                "Commissioner of Lobbying of Canada (Office of the)" => "Commissioner of Lobbying of Canada (Office of the)",
                "Commissioner of Official Languages (Office of the)" => "Commissioner of Official Languages (Office of the)",
                "Communications Research Centre Canada" => "Communications Research Centre Canada",
                "Communications Security Establishment Canada" => "Communications Security Establishment Canada",
                "Communications Security Establishment Commissioner (Office of the)" => "Communications Security Establishment Commissioner (Office of the)",
                "Competition Bureau Canada" => "Competition Bureau Canada",
                "Competition Tribunal" => "Competition Tribunal",
                "Conflict of Interest and Ethics Commissioner (Office of the)" => "Conflict of Interest and Ethics Commissioner (Office of the)",
                "Copyright Board Canada" => "Copyright Board Canada",
                "CORCAN" => "CORCAN",
                "Correctional Investigator Canada" => "Correctional Investigator Canada",
                "Correctional Service Canada" => "Correctional Service Canada",
                "Courts Administration Service" => "Courts Administration Service",
                "Currency Museum" => "Currency Museum",
                "Defence Construction Canada" => "Defence Construction Canada",
                "Defence Research and Development Canada" => "Defence Research and Development Canada",
                "Democratic Institutions" => "Democratic Institutions",
                "Elections Canada" => "Elections Canada",
                "Employment and Social Development Canada" => "Employment and Social Development Canada",
                "Environment and Climate Change Canada" => "Environment and Climate Change Canada",
                "Environmental Protection Review Canada" => "Environmental Protection Review Canada",
                "Export Development Canada" => "Export Development Canada",
                "Farm Credit Canada" => "Farm Credit Canada",
                "Farm Products Council of Canada" => "Farm Products Council of Canada",
                "Federal Bridge Corporation" => "Federal Bridge Corporation",
                "Federal Court of Appeal" => "Federal Court of Appeal",
                "Federal Court of Canada" => "Federal Court of Canada",
                "Federal Economic Development Agency for Southern Ontario" => "Federal Economic Development Agency for Southern Ontario",
                "Federal Economic Development Initiative for Northern Ontario (FedNor)" => "Federal Economic Development Initiative for Northern Ontario (FedNor)",
                "Federal Ombudsman for Victims Of Crime (Office of the)" => "Federal Ombudsman for Victims Of Crime (Office of the)",
                "Finance Canada (Department of)" => "Finance Canada (Department of)",
                "Financial Consumer Agency of Canada" => "Financial Consumer Agency of Canada",
                "Financial Transactions and Reports Analysis Centre of Canada" => "Financial Transactions and Reports Analysis Centre of Canada",
                "Fisheries and Oceans Canada" => "Fisheries and Oceans Canada",
                "Freshwater Fish Marketing Corporation" => "Freshwater Fish Marketing Corporation",
                "Geographical Names Board of Canada" => "Geographical Names Board of Canada",
                "Geomatics Canada" => "Geomatics Canada",
                "Global Affairs Canada" => "Global Affairs Canada",
                "Governor General of Canada" => "Governor General of Canada",
                "Great Lakes Pilotage Authority Canada" => "Great Lakes Pilotage Authority Canada",
                "Health Canada" => "Health Canada",
                "Historic Sites and Monuments Board of Canada" => "Historic Sites and Monuments Board of Canada",
                "Human Rights Tribunal of Canada" => "Human Rights Tribunal of Canada",
                "Immigration and Refugee Board of Canada" => "Immigration and Refugee Board of Canada",
                "Immigration, Refugees and Citizenship Canada" => "Immigration, Refugees and Citizenship Canada",
                "Indian Oil and Gas Canada" => "Indian Oil and Gas Canada",
                "Indian Residential Schools Truth and Reconciliation Commission" => "Indian Residential Schools Truth and Reconciliation Commission",
                "Indigenous and Northern Affairs Canada" => "Indigenous and Northern Affairs Canada",
                "Industrial Technologies Office" => "Industrial Technologies Office",
                "Information Commissioner (Office of the)" => "Information Commissioner (Office of the)",
                "Infrastructure Canada" => "Infrastructure Canada",
                "Innovation, Science and Economic Development Canada" => "Innovation, Science and Economic Development Canada",
                "Intergovernmental Affairs (Department of)" => "Intergovernmental Affairs (Department of)",
                "International Development Research Centre" => "International Development Research Centre",
                "Jacques Cartier and Champlain Bridges Inc." => "Jacques Cartier and Champlain Bridges Inc.",
                "Justice Canada (Department of)" => "Justice Canada (Department of)",
                "Labour Program" => "Labour Program",
                "Laurentian Pilotage Authority Canada" => "Laurentian Pilotage Authority Canada",
                "Leader of the Government in the House of Commons" => "Leader of the Government in the House of Commons",
                "Library and Archives Canada" => "Library and Archives Canada",
                "Marine Atlantic" => "Marine Atlantic",
                "Measurement Canada" => "Measurement Canada",
                "Military Grievances External Review Committee" => "Military Grievances External Review Committee",
                "Military Police Complaints Commission of Canada" => "Military Police Complaints Commission of Canada",
                "National Arts Centre" => "National Arts Centre",
                "National Battlefields Commission" => "National Battlefields Commission",
                "National Capital Commission" => "National Capital Commission",
                "National Defence" => "National Defence",
                "National Defence and the Canadian Forces Ombudsperson (Office of the)" => "National Defence and the Canadian Forces Ombudsperson (Office of the)",
                "National Energy Board" => "National Energy Board",
                "National Film Board" => "National Film Board",
                "National Gallery of Canada" => "National Gallery of Canada",
                "National Museum of Science and Technology" => "National Museum of Science and Technology",
                "National Research Council Canada" => "National Research Council Canada",
                "National Search and Rescue Secretariat" => "National Search and Rescue Secretariat",
                "National Seniors Council" => "National Seniors Council",
                "Natural Resources Canada" => "Natural Resources Canada",
                "Natural Sciences and Engineering Research Canada" => "Natural Sciences and Engineering Research Canada",
                "Northern Pipeline Agency Canada" => "Northern Pipeline Agency Canada",
                "Occupational Health and Safety Tribunal Canada" => "Occupational Health and Safety Tribunal Canada",
                "Pacific Pilotage Authority Canada" => "Pacific Pilotage Authority Canada",
                "Parks Canada" => "Parks Canada",
                "Parliament of Canada" => "Parliament of Canada",
                "Parole Board of Canada" => "Parole Board of Canada",
                "Passport Canada" => "Passport Canada",
                "Patented Medicine Prices Review Board Canada" => "Patented Medicine Prices Review Board Canada",
                "Polar Knowledge Canada" => "Polar Knowledge Canada",
                "PPP Canada" => "PPP Canada",
                "Prime Minister of Canada" => "Prime Minister of Canada",
                "Privacy Commissioner (Office of the)" => "Privacy Commissioner (Office of the)",
                "Privy Council Office" => "Privy Council Office",
                "Procurement Ombudsman (Office of the)" => "Procurement Ombudsman (Office of the)",
                "Public Health Agency of Canada" => "Public Health Agency of Canada",
                "Public Prosecution Service of Canada" => "Public Prosecution Service of Canada",
                "Public Safety Canada" => "Public Safety Canada",
                "Public Sector Integrity Commissioner of Canada (Office of the)" => "Public Sector Integrity Commissioner of Canada (Office of the)",
                "Public Sector Pension Investment Board" => "Public Sector Pension Investment Board",
                "Public Servants Disclosure Protection Tribunal Canada" => "Public Servants Disclosure Protection Tribunal Canada",
                "Public Service Commission of Canada" => "Public Service Commission of Canada",
                "Public Service Labour Relations and Employment Board" => "Public Service Labour Relations and Employment Board",
                "Public Services and Procurement Canada" => "Public Services and Procurement Canada",
                "Receiver General for Canada" => "Receiver General for Canada",
                "Registry of the Specific Claims Tribunal of Canada" => "Registry of the Specific Claims Tribunal of Canada",
                "Ridley Terminals Inc." => "Ridley Terminals Inc.",
                "Royal Canadian Air Force" => "Royal Canadian Air Force",
                "Royal Canadian Mint" => "Royal Canadian Mint",
                "Royal Canadian Mounted Police" => "Royal Canadian Mounted Police",
                "Royal Canadian Mounted Police External Review Committee" => "Royal Canadian Mounted Police External Review Committee",
                "Royal Canadian Navy" => "Royal Canadian Navy",
                "Royal Military College of Canada" => "Royal Military College of Canada",
                "Secretary to the Governor General (Office of the)" => "Secretary to the Governor General (Office of the)",
                "Security Intelligence Review Committee" => "Security Intelligence Review Committee",
                "Seniors" => "Seniors",
                "Service Canada" => "Service Canada",
                "Shared Services Canada" => "Shared Services Canada",
                "Ship-Source Oil Pollution Fund" => "Ship-Source Oil Pollution Fund",
                "Social Sciences and Humanities Research Council of Canada" => "Social Sciences and Humanities Research Council of Canada",
                "Social Security Tribunal of Canada" => "Social Security Tribunal of Canada",
                "Sport Canada" => "Sport Canada",
                "Standards Council of Canada" => "Standards Council of Canada",
                "Statistics Canada" => "Statistics Canada",
                "Status of Women Canada" => "Status of Women Canada",
                "Superintendent of Bankruptcy Canada (Office of the)" => "Superintendent of Bankruptcy Canada (Office of the)",
                "Superintendent of Financial Institutions Canada (Office of the)" => "Superintendent of Financial Institutions Canada (Office of the)",
                "Supreme Court of Canada" => "Supreme Court of Canada",
                "Tax Court of Canada" => "Tax Court of Canada",
                "Taxpayers' Ombudsman (Office of the)" => "Taxpayers' Ombudsman (Office of the)",
                "Telefilm Canada" => "Telefilm Canada",
                "Translation Bureau" => "Translation Bureau",
                "Transport Canada" => "Transport Canada",
                "Transportation Appeal Tribunal of Canada" => "Transportation Appeal Tribunal of Canada",
                "Transportation Safety Board of Canada" => "Transportation Safety Board of Canada",
                "Treasury Board of Canada Secretariat" => "Treasury Board of Canada Secretariat",
                "Veterans Affairs Canada" => "Veterans Affairs Canada",
                "Veterans' Ombudsman (Office of the)" => "Veterans' Ombudsman (Office of the)",
                "Veterans Review and Appeal Board Canada" => "Veterans Review and Appeal Board Canada",
                "VIA Rail Canada Inc." => "VIA Rail Canada Inc.",
                "Virtual Museum of Canada" => "Virtual Museum of Canada",
                "Western Economic Diversification Canada" => "Western Economic Diversification Canada");
            } else {
                $federal_departments = array("Canadian Air Transport Security Authority" => "Administration canadienne de la sûreté du transport aérien",
                "Atlantic Pilotage Authority Canada" => "Administration de pilotage de l'Atlantique Canada",
                "Great Lakes Pilotage Authority Canada" => "Administration de pilotage des Grands Lacs Canada",
                "Laurentian Pilotage Authority Canada" => "Administration de pilotage des Laurentides Canada",
                "Pacific Pilotage Authority Canada" => "Administration de pilotage du Pacifique Canada",
                "Northern Pipeline Agency Canada" => "Administration du pipe-line du Nord Canada",
                "Indigenous and Northern Affairs Canada" => "Affaires autochtones et du Nord Canada",
                "Intergovernmental Affairs (Department of)" => "Affaires intergouvernementales",
                "Global Affairs Canada" => "Affaires mondiales Canada",
                "Canadian Northern Economic Development Agency" => "Agence canadienne de développement économique du Nord",
                "Canadian Environmental Assessment Agency" => "Agence canadienne d'évaluation environnementale",
                "Canadian Food Inspection Agency" => "Agence canadienne d'inspection des aliments",
                "Canadian Pari-Mutuel Agency" => "Agence canadienne du pari mutuel",
                "Financial Consumer Agency of Canada" => "Agence de la consommation en matière financière du Canada",
                "Public Health Agency of Canada" => "Agence de la santé publique du Canada",
                "Canadian Forces Housing Agency" => "Agence de logement des Forces canadiennes",
                "Atlantic Canada Opportunities Agency" => "Agence de promotion économique du Canada atlantique",
                "Canada Border Services Agency" => "Agence des services frontaliers du Canada",
                "Canada Revenue Agency" => "Agence du revenu du Canada",
                "Federal Economic Development Agency for Southern Ontario" => "Agence fédérale de développement économique pour le Sud de l'Ontario",
                "Canadian Space Agency" => "Agence spatiale canadienne",
                "Agriculture and Agri-Food Canada" => "Agriculture et Agroalimentaire Canada",
                "Seniors" => "Aînés",
                "Veterans Affairs Canada" => "Anciens Combattants Canada",
                "Canadian Army" => "Armée canadienne",
                "Royal Canadian Air Force" => "Aviation royale canadienne",
                "Business Development Bank of Canada" => "Banque de développement du Canada",
                "Bank of Canada" => "Banque du Canada",
                "Library and Archives Canada" => "Bibliothèque et Archives Canada",
                "Competition Bureau Canada" => "Bureau de la concurrence Canada",
                "Transportation Safety Board of Canada" => "Bureau de la sécurité des transports du Canada",
                "Translation Bureau" => "Bureau de la traduction",
                "Procurement Ombudsman (Office of the)" => "Bureau de l'ombudsman de l'approvisionnement",
                "Taxpayers' Ombudsman (Office of the)" => "Bureau de l'ombudsman des contribuables",
                "Federal Ombudsman for Victims Of Crime (Office of the)" => "Bureau de l'ombudsman fédéral des victimes d'actes criminels",
                "Communications Security Establishment Commissioner (Office of the)" => "Bureau du commissaire du Centre de la sécurité des télécommunications",
                "Privy Council Office" => "Bureau du Conseil privé",
                "Chief Electoral Officer (Office of the)" => "Bureau du directeur général des élections",
                "Secretary to the Governor General (Office of the)" => "Bureau du secrétaire du gouverneur général",
                "Superintendent of Bankruptcy Canada (Office of the)" => "Bureau du surintendant des faillites Canada",
                "Superintendent of Financial Institutions Canada (Office of the)" => "Bureau du surintendant des institutions financières Canada",
                "Auditor General of Canada (Office of the)" => "Bureau du vérificateur général du Canada",
                "Ship-Source Oil Pollution Fund" => "Caisse d'indemnisation des dommages dus à la pollution par les hydrocarbures causée par les navires",
                "Canada Centre for Inland Waters" => "Centre canadien des eaux intérieures",
                "Canadian Centre for Occupational Health and Safety" => "Centre canadien d'hygiène et de sécurité au travail",
                "Financial Transactions and Reports Analysis Centre of Canada" => "Centre d'analyse des opérations et déclarations financières du Canada",
                "Communications Security Establishment Canada" => "Centre de la sécurité des télécommunications Canada",
                "International Development Research Centre" => "Centre de recherches pour le développement international",
                "Communications Research Centre Canada" => "Centre de recherches sur les communications Canada",
                "Canada Firearms Centre" => "Centre des armes à feu Canada",
                "National Arts Centre" => "Centre national des arts",
                "Canada Research Chairs" => "Chaires de recherche du Canada",
                "Canadian Police College" => "Collège canadien de police",
                "Royal Military College of Canada" => "Collège militaire royal du Canada",
                "Security Intelligence Review Committee" => "Comité de surveillance des activités de renseignement de sécurité",
                "Royal Canadian Mounted Police External Review Committee" => "Comité externe d'examen de la Gendarmerie royale du Canada",
                "Military Grievances External Review Committee" => "Comité externe d'examen des griefs militaires",
                "Commissioner for Federal Judicial Affairs Canada (Office of the)" => "Commissariat à la magistrature fédérale Canada",
                "Privacy Commissioner (Office of the)" => "Commissariat à la protection de la vie privée au Canada",
                "Information Commissioner (Office of the)" => "Commissariat à l'information au Canada",
                "Public Sector Integrity Commissioner of Canada (Office of the)" => "Commissariat à l'intégrité du secteur public du Canada",
                "Commissioner of Lobbying of Canada (Office of the)" => "Commissariat au lobbying du Canada",
                "Conflict of Interest and Ethics Commissioner (Office of the)" => "Commissariat aux conflits d'intérêts et à l'éthique",
                "Commissioner of Official Languages (Office of the)" => "Commissariat aux langues officielles",
                "Canadian Nuclear Safety Commission" => "Commission canadienne de sûreté nucléaire",
                "Canadian Human Rights Commission" => "Commission canadienne des droits de la personne",
                "Canadian Grain Commission" => "Commission canadienne des grains",
                "Canadian Cultural Property Export Review Board" => "Commission canadienne d'examen des exportations de biens culturels",
                "Canadian Dairy Commission" => "Commission canadienne du lait",
                "Canadian Tourism Commission" => "Commission canadienne du tourisme",
                "Civilian Review and Complaints Commission for the RCMP" => "Commission civile d'examen et de traitement des plaintes relatives à la GRC",
                "National Capital Commission" => "Commission de la capitale nationale",
                "Public Service Commission of Canada" => "Commission de la fonction publique du Canada",
                "Canada Employment Insurance Commission" => "Commission de l'assurance-emploi du Canada",
                "Immigration and Refugee Board of Canada" => "Commission de l'immigration et du statut de réfugié du Canada",
                "Canada Agricultural Review Tribunal" => "Commission de révision agricole du Canada",
                "Geographical Names Board of Canada" => "Commission de toponymie du Canada",
                "Indian Residential Schools Truth and Reconciliation Commission" => "Commission de vérité et de réconciliation relative aux pensionnats indiens",
                "National Battlefields Commission" => "Commission des champs de bataille nationaux",
                "Parole Board of Canada" => "Commission des libérations conditionnelles du Canada",
                "Historic Sites and Monuments Board of Canada" => "Commission des lieux et monuments historiques du Canada",
                "Public Service Labour Relations and Employment Board" => "Commission des relations de travail et de l'emploi dans la fonction publique",
                "Military Police Complaints Commission of Canada" => "Commission d'examen des plaintes concernant la police militaire du Canada",
                "Copyright Board Canada" => "Commission du droit d'auteur Canada",
                "Status of Women Canada" => "Condition féminine Canada",
                "Canadian Judicial Council" => "Conseil canadien de la magistrature",
                "Standards Council of Canada" => "Conseil canadien des normes",
                "Canada Industrial Relations Board" => "Conseil canadien des relations industrielles",
                "Canadian Radio-Television and Telecommunications Commission" => "Conseil de la radiodiffusion et des télécommunications canadiennes",
                "Natural Sciences and Engineering Research Canada" => "Conseil de recherches en sciences et en génie Canada",
                "Social Sciences and Humanities Research Council of Canada" => "Conseil de recherches en sciences humaines du Canada",
                "Canada Council for the Arts" => "Conseil des arts du Canada",
                "Farm Products Council of Canada" => "Conseil des produits agricoles du Canada",
                "Patented Medicine Prices Review Board Canada" => "Conseil d'examen du prix des médicaments brevetés Canada",
                "National Research Council Canada" => "Conseil national de recherches Canada",
                "National Seniors Council" => "Conseil national des aînés",
                "Defence Construction Canada" => "Construction de Défense Canada",
                "CORCAN" => "CORCAN",
                "Canadian Commercial Corporation" => "Corporation commerciale canadienne",
                "Canada Development Investment Corporation" => "Corporation de développement des investissements du Canada",
                "Tax Court of Canada" => "Cour canadienne de l'impôt",
                "Federal Court of Appeal" => "Cour d'appel fédérale",
                "Federal Court of Canada" => "Cour fédérale",
                "Supreme Court of Canada" => "Cour suprême du Canada",
                "National Defence" => "Défense nationale",
                "Canada Economic Development for Quebec Regions" => "Développement économique Canada pour les régions du Québec",
                "Western Economic Diversification Canada" => "Diversification de l'économie de l'Ouest Canada",
                "Canada School of Public Service" => "École de la fonction publique du Canada",
                "Elections Canada" => "Élections Canada",
                "Employment and Social Development Canada" => "Emploi et Développement social Canada",
                "Atomic Energy of Canada Limited" => "Énergie atomique du Canada, Limitée",
                "Correctional Investigator Canada" => "Enquêteur correctionnel Canada",
                "Aboriginal Business Canada" => "Entreprise autochtone Canada",
                "Environment and Climate Change Canada" => "Environnement et Changement climatique Canada",
                "Export Development Canada" => "Exportation et développement Canada",
                "Farm Credit Canada" => "Financement agricole Canada",
                "Finance Canada (Department of)" => "Finances Canada, Ministère des",
                "Canadian Race Relations Foundation" => "Fondation canadienne des relations raciales",
                "Canadian Coast Guard" => "Garde côtière canadienne",
                "Canada Gazette" => "Gazette du Canada",
                "Royal Canadian Mounted Police" => "Gendarmerie royale du Canada",
                "Geomatics Canada" => "Géomatique Canada",
                "Governor General of Canada" => "Gouverneur général du Canada",
                "Registry of the Specific Claims Tribunal of Canada" => "Greffe du Tribunal des revendications particulières du Canada",
                "Clerk of the Privy Council" => "Greffier du Conseil privé",
                "Immigration, Refugees and Citizenship Canada" => "Immigration, Réfugiés, et Citoyenneté Canada",
                "Infrastructure Canada" => "Infrastructure Canada",
                "Federal Economic Development Initiative for Northern Ontario (FedNor)" => "Initiative fédérale de développement économique pour le Nord de l'Ontario (FedNor)",
                "Innovation, Science and Economic Development Canada" => "Innovation, Sciences et Développement économique Canada",
                "Canadian Conservation Institute" => "Institut canadien de conservation",
                "Democratic Institutions" => "Institutions démocratiques",
                "Canadian Institutes of Health Research" => "Instituts de recherche en santé du Canada",
                "Public Sector Pension Investment Board" => "Investissement des régimes de pensions du secteur public",
                "Justice Canada (Department of)" => "Justice Canada, Ministère de la",
                "Leader of the Government in the House of Commons" => "Leader du gouvernement à la Chambre des communes",
                "Marine Atlantic" => "Marine Atlantique",
                "Royal Canadian Navy" => "Marine royale canadienne",
                "Measurement Canada" => "Mesures Canada",
                "Royal Canadian Mint" => "Monnaie royale canadienne",
                "Canadian War Museum" => "Musée canadien de la guerre",
                "Canadian Museum of Nature" => "Musée canadien de la nature",
                "Canadian Museum of Contemporary Photography" => "Musée canadien de la photographie contemporaine",
                "Canadian Museum of History" => "Musée canadien de l'histoire",
                "Canadian Museum of Immigration at Pier 21" => "Musée canadien de l'immigration du Quai 21",
                "Canadian Museum for Human Rights" => "Musée canadien pour les droits de la personne",
                "Currency Museum" => "Musée de la Banque du Canada",
                "Canada Agriculture and Food Museum" => "Musée de l'agriculture et de l'alimentation du Canada",
                "Canada Aviation and Space Museum" => "Musée de l'aviation et de l'espace du Canada",
                "National Gallery of Canada" => "Musée des beaux-arts du Canada",
                "Virtual Museum of Canada" => "Musée virtuel du Canada",
                "National Museum of Science and Technology" => "Musées des sciences et de la technologie du Canada",
                "Freshwater Fish Marketing Corporation" => "Office de commercialisation du poisson d'eau douce",
                "Canadian Intellectual Property Office" => "Office de la propriété intellectuelle du Canada",
                "Canadian General Standards Board" => "Office des normes générales du Canada",
                "Industrial Technologies Office" => "Office des technologies industrielles",
                "Canadian Transportation Agency" => "Office des transports du Canada",
                "Canada Pension Plan Investment Board" => "Office d'investissement du régime de pensions du Canada",
                "National Energy Board" => "Office national de l'énergie",
                "National Film Board" => "Office national du film",
                "National Defence and the Canadian Forces Ombudsperson (Office of the)" => "Ombudsman de la Défense nationale et des Forces canadiennes",
                "Veterans' Ombudsman (Office of the)" => "Ombudsman des vétérans",
                "Canadian Cadet Organizations" => "Organisations de cadets du Canada",
                "Parks Canada" => "Parcs Canada",
                "Parliament of Canada" => "Parlement du Canada",
                "Passport Canada" => "Passeport Canada",
                "Canadian Heritage" => "Patrimoine canadien",
                "Fisheries and Oceans Canada" => "Pêches et Océans Canada",
                "Indian Oil and Gas Canada" => "Pétrole et gaz des Indiens du Canada",
                "Blue Water Bridge Canada" => "Pont Blue Water Canada",
                "Jacques Cartier and Champlain Bridges Inc." => "Ponts Jacques-Cartier et Champlain Inc.",
                "Canada Post" => "Postes Canada",
                "PPP Canada" => "PPP Canada",
                "Prime Minister of Canada" => "Premier ministre du Canada",
                "Labour Program" => "Programme du travail",
                "Receiver General for Canada" => "Receveur général du Canada",
                "Defence Research and Development Canada" => "Recherche et développement pour la Défense Canada",
                "Canadian Heritage Information Network" => "Réseau canadien d'information sur le patrimoine",
                "Natural Resources Canada" => "Ressources naturelles Canada",
                "Environmental Protection Review Canada" => "Révision de la protection de l'environnement Canada",
                "Ridley Terminals Inc." => "Ridley Terminals Inc.",
                "Health Canada" => "Santé Canada",
                "Polar Knowledge Canada" => "Savoir polaire Canada",
                "Canadian Intergovernmental Conference Secretariat" => "Secrétariat des conférences intergouvernementales canadiennes",
                "Treasury Board of Canada Secretariat" => "Secrétariat du Conseil du Trésor du Canada",
                "National Search and Rescue Secretariat" => "Secrétariat national recherche et sauvetage",
                "Public Safety Canada" => "Sécurité publique Canada",
                "Courts Administration Service" => "Service administratif des tribunaux judiciaires",
                "Service Canada" => "Service Canada",
                "Administrative Tribunals Support Service of Canada" => "Service canadien d'appui aux tribunaux administratifs",
                "Canadian Security Intelligence Service" => "Service canadien du renseignement de sécurité",
                "Correctional Service Canada" => "Service correctionnel Canada",
                "Canadian Trade Commissioner Service" => "Service des délégués commerciaux du Canada",
                "Public Prosecution Service of Canada" => "Service des poursuites pénales du Canada",
                "Shared Services Canada" => "Services partagés Canada",
                "Public Services and Procurement Canada" => "Services publics et Approvisionnement Canada",
                "Canada Mortgage and Housing Corporation" => "Société canadienne d'hypothèques et de logement",
                "Canada Deposit Insurance Corporation" => "Société d'assurance-dépôts du Canada",
                "Canada Science and Technology Museum Corporation" => "Société des musées de sciences et technologies du Canada",
                "Federal Bridge Corporation" => "Société des ponts fédéraux",
                "Canada Lands Company Limited" => "Société immobilière du Canada Limitée",
                "Sport Canada" => "Sport Canada",
                "Statistics Canada" => "Statistique Canada",
                "Telefilm Canada" => "Téléfilm Canada",
                "Transport Canada" => "Transports Canada",
                "Canadian International Trade Tribunal" => "Tribunal canadien du commerce extérieur",
                "Transportation Appeal Tribunal of Canada" => "Tribunal d'appel des transports du Canada",
                "Competition Tribunal" => "Tribunal de la concurrence",
                "Public Servants Disclosure Protection Tribunal Canada" => "Tribunal de la protection des fonctionnaires divulgateurs Canada",
                "Social Security Tribunal of Canada" => "Tribunal de la sécurité sociale du Canada",
                "Occupational Health and Safety Tribunal Canada" => "Tribunal de santé et sécurité au travail Canada",
                "Veterans Review and Appeal Board Canada" => "Tribunal des anciens combattants (révision et appel) Canada",
                "Human Rights Tribunal of Canada" => "Tribunal des droits de la personne du Canada",
                "VIA Rail Canada Inc." => "VIA Rail Canada Inc.");
            }

            /*
            $value = explode(" / ", $value);

            $key = array_search($value[0], $departments);
            if ($key === false)
                $key = array_search($value[1], $departments);
            */

            $key = array_search($value, $federal_departments);
            if ($key === false)
                $key = array_search($value, $federal_departments);

            echo elgg_view('input/select', array(
                'name' => $field,
                'id' => $field,
                'class' => ' gcconnex-basic-' . $field,
                'value' => $key,
                'options_values' => $federal_departments,
            ));
        
        // provincial input field
        } else if ($field == 'provincial') {

            echo "<label for='{$field}' class='basic-profile-label {$field}-label {$field}'>" . elgg_echo("gcconnex_profile:basic:{$field}")."</label>";
            echo '<div class="basic-profile-field provincial">';

            $provincial_departments = array();
            if (get_current_language() == 'en'){
                $provincial_departments = array("Alberta" => "Alberta",
                "British Columbia" => "British Columbia",
                "Manitoba" => "Manitoba",
                "New Brunswick" => "New Brunswick",
                "Newfoundland and Labrador" => "Newfoundland and Labrador",
                "Northwest Territories" => "Northwest Territories",
                "Nova Scotia" => "Nova Scotia",
                "Nunavut" => "Nunavut",
                "Ontario" => "Ontario",
                "Prince Edward Island" => "Prince Edward Island",
                "Quebec" => "Quebec",
                "Saskatchewan" => "Saskatchewan",
                "Yukon" => "Yukon");
            } else {
                $provincial_departments = array("Alberta" => "Alberta",
                "British Columbia" => "Colombie-Britannique",
                "Prince Edward Island" => "Île-du-Prince-Édouard",
                "Manitoba" => "Manitoba",
                "New Brunswick" => "Nouveau-Brunswick",
                "Nova Scotia" => "Nouvelle-Écosse",
                "Nunavut" => "Nunavut",
                "Ontario" => "Ontario",
                "Quebec" => "Québec",
                "Saskatchewan" => "Saskatchewan",
                "Newfoundland and Labrador" => "Terre-Neuve-et-Labrador",
                "Northwest Territories" => "Territoires du Nord-Ouest",
                "Yukon" => "Yukon");
            }

            echo elgg_view('input/select', array(
                'name' => $field,
                'id' => $field,
                'class' => ' gcconnex-basic-' . $field,
                'value' => $value,
                'options_values' => $provincial_departments,
            ));

            echo "</div></div><div class='basic-profile-field-wrapper col-xs-12'>";
            echo "<label for='alberta' class='basic-profile-label ministry alberta'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field alberta">';

            $alberta_ministries = array("Advanced Education" => "Advanced Education",
            "Agriculture and Forestry" => "Agriculture and Forestry",
            "Corporate Human Resourcing" => "Corporate Human Resourcing",
            "Culture and Tourism" => "Culture and Tourism",
            "Economic Development and Trade" => "Economic Development and Trade",
            "Education" => "Education",
            "Energy" => "Energy",
            "Environment and Parks" => "Environment and Parks",
            "Health" => "Health",
            "Human Services" => "Human Services",
            "Indigenous Relations" => "Indigenous Relations",
            "Infrastructure" => "Infrastructure",
            "Justice and Solicitor General" => "Justice and Solicitor General",
            "Labour" => "Labour",
            "Municipal Affairs" => "Municipal Affairs",
            "Seniors and Housing" => "Seniors and Housing",
            "Service Alberta" => "Service Alberta",
            "Status of Women" => "Status of Women",
            "Transportation" => "Transportation",
            "Treasury Board and Finance" => "Treasury Board and Finance");

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'alberta',
                'class' => 'form-control ministry',
                'options_values' => $alberta_ministries,
            ));

            echo "</div></div><div class='basic-profile-field-wrapper col-xs-12'>";
            echo "<label for='british-columbia' class='basic-profile-label ministry british-columbia'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field british-columbia">';

            $bc_ministries = array("Aboriginal Relations & Reconciliation" => "Aboriginal Relations & Reconciliation",
            "Advanced Education" => "Advanced Education",
            "Agriculture" => "Agriculture",
            "Children & Family Development" => "Children & Family Development",
            "Community, Sport & Cultural Development" => "Community, Sport & Cultural Development",
            "Education" => "Education",
            "Energy & Mines" => "Energy & Mines",
            "Environment" => "Environment",
            "Finance" => "Finance",
            "Forests, Lands & Natural Resource Operations" => "Forests, Lands & Natural Resource Operations",
            "Health" => "Health",
            "International Trade" => "International Trade",
            "Jobs, Tourism & Skills Training" => "Jobs, Tourism & Skills Training",
            "Justice" => "Justice",
            "Natural Gas Development" => "Natural Gas Development",
            "Public Safety & Solicitor General" => "Public Safety & Solicitor General",
            "Small Business, Red Tape Reduction" => "Small Business, Red Tape Reduction",
            "Social Development & Social Innovation" => "Social Development & Social Innovation",
            "Technology, Innovation & Citizens' Services" => "Technology, Innovation & Citizens' Services",
            "Transportation & Infrastructure" => "Transportation & Infrastructure");

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'british-columbia',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $bc_ministries),
            ));

            echo "</div></div>";
            echo "<label for='manitoba' class='basic-profile-label ministry manitoba'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field manitoba">';

            $manitoba_ministries = array();
            if (get_current_language() == 'en'){
                $manitoba_ministries = array("Agriculture" => "Agriculture",
                "Civil Service Commission" => "Civil Service Commission",
                "Crown Services" => "Crown Services",
                "Education and Training" => "Education and Training",
                "Families" => "Families",
                "Finance" => "Finance",
                "Growth, Enterprise and Trade" => "Growth, Enterprise and Trade",
                "Health, Seniors and Active Living" => "Health, Seniors and Active Living",
                "Indigenous and Municipal Relations" => "Indigenous and Municipal Relations",
                "Infrastructure" => "Infrastructure",
                "Intergovernmental Affairs and International Relations" => "Intergovernmental Affairs and International Relations",
                "Justice" => "Justice",
                "Sport, Culture and Heritage" => "Sport, Culture and Heritage",
                "Sustainable Development" => "Sustainable Development");
            } else {
                $manitoba_ministries = array("Intergovernmental Affairs and International Relations" => "Affaires intergouvernementales et relations internationales",
                "Agriculture" => "Agriculture",
                "Civil Service Commission" => "Commission de la fonction publique",
                "Growth, Enterprise and Trade" => "Croissance, Entreprise et Commerce",
                "Sustainable Development" => "Développement durable",
                "Education and Training" => "Éducation et Formation",
                "Families" => "Familles",
                "Finance" => "Finances",
                "Infrastructure" => "Infrastructure",
                "Justice" => "Justice",
                "Indigenous and Municipal Relations" => "Relations avec les Autochtones et les municipalités",
                "Health, Seniors and Active Living" => "Santé, Aînés et Vie active",
                "Crown Services" => "Services de la Couronne",
                "Sport, Culture and Heritage" => "Sport, Culture et Patrimoine");
            }

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'manitoba',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $manitoba_ministries),
            ));

            echo "</div>";
            echo "<label for='new-brunswick' class='basic-profile-label ministry new-brunswick'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field new-brunswick">';

            $new_brunswick_ministries = array();
            if (get_current_language() == 'en'){
                $new_brunswick_ministries = array("Aboriginal Affairs" => "Aboriginal Affairs",
                "Agriculture, Aquaculture and Fisheries" => "Agriculture, Aquaculture and Fisheries",
                "Education and Early Childhood Development" => "Education and Early Childhood Development",
                "Emergency Measures Organization" => "Emergency Measures Organization",
                "Energy and Resource Development" => "Energy and Resource Development",
                "Environment and Local Government" => "Environment and Local Government",
                "Executive Council Office" => "Executive Council Office",
                "Finance" => "Finance",
                "Health" => "Health",
                "Intergovernmental Affairs" => "Intergovernmental Affairs",
                "Justice and Public Safety" => "Justice and Public Safety",
                "Office of the Attorney General" => "Office of the Attorney General",
                "Office of the Premier" => "Office of the Premier",
                "Opportunities New Brunswick" => "Opportunities New Brunswick",
                "Post-Secondary Education, Training and Labour" => "Post-Secondary Education, Training and Labour",
                "Regional Development Corporation" => "Regional Development Corporation",
                "Service New Brunswick" => "Service New Brunswick",
                "Social Development" => "Social Development",
                "Tourism, Heritage and Culture" => "Tourism, Heritage and Culture",
                "Transportation and Infrastructure" => "Transportation and Infrastructure",
                "Treasury Board" => "Treasury Board",
                "Women's Equality" => "Women's Equality");
            } else {
                $new_brunswick_ministries = array("Aboriginal Affairs" => "Affaires autochtones",
                "Intergovernmental Affairs" => "Affaires intergouvernementales",
                "Agriculture, Aquaculture and Fisheries" => "Agriculture, Aquaculture et Pêches",
                "Executive Council Office" => "Bureau du Conseil exécutif",
                "Office of the Attorney General" => "Cabinet du procureur général",
                "Office of the Premier" => "Cabinet du premier ministre",
                "Treasury Board" => "Conseil du Trésor",
                "Energy and Resource Development" => "Développement de l'énergie et des ressources",
                "Social Development" => "Développement social",
                "Education and Early Childhood Development" => "Éducation et Développement de la petite enfance",
                "Post-Secondary Education, Training and Labour" => "Éducation postsecondaire, Formation et Travail",
                "Women's Equality" => "Égalité des femmes",
                "Environment and Local Government" => "Environnement et Gouvernements locaux",
                "Finance" => "Finances",
                "Justice and Public Safety" => "Justice et Sécurité publique",
                "Opportunities New Brunswick" => "Opportunités Nouveau-Brunswick",
                "Emergency Measures Organization" => "Organisation des mesures d'urgence",
                "Health" => "Santé",
                "Service New Brunswick" => "Service Nouveau-Brunswick",
                "Regional Development Corporation" => "Société de développement régional",
                "Tourism, Heritage and Culture" => "Tourisme, Patrimoine et Culture",
                "Transportation and Infrastructure" => "Transports et Infrastructure");
            }

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'new-brunswick',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $new_brunswick_ministries),
            ));

            echo "</div>";
            echo "<label for='newfoundland-and-labrador' class='basic-profile-label ministry newfoundland-and-labrador'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field newfoundland-and-labrador">';

            $newfoundland_ministries = array("Advanced Education, Skills and Labour" => "Advanced Education, Skills and Labour",
            "Board of Commissioners of Public Utilities" => "Board of Commissioners of Public Utilities",
            "Business, Tourism, Culture and Rural Development" => "Business, Tourism, Culture and Rural Development",
            "Children, Seniors and Social Development" => "Children, Seniors and Social Development",
            "Commissioner for Legislative Standards" => "Commissioner for Legislative Standards",
            "Education and Early Childhood Development" => "Education and Early Childhood Development",
            "Electoral Districts Boundaries Commission" => "Electoral Districts Boundaries Commission",
            "Environment and Climate Change" => "Environment and Climate Change",
            "Executive Council" => "Executive Council",
            "Finance" => "Finance",
            "Fisheries, Forestry and Agrifoods" => "Fisheries, Forestry and Agrifoods",
            "Government Purchasing Agency" => "Government Purchasing Agency",
            "Health and Community Services" => "Health and Community Services",
            "Human Rights Commission" => "Human Rights Commission",
            "Justice and Public Safety" => "Justice and Public Safety",
            "Labour Relations Board" => "Labour Relations Board",
            "Multi-Materials Stewardship Board" => "Multi-Materials Stewardship Board",
            "Municipal Affairs" => "Municipal Affairs",
            "Natural Resources" => "Natural Resources",
            "Newfoundland and Labrador Film Development Corporation" => "Newfoundland and Labrador Film Development Corporation",
            "Newfoundland and Labrador Housing Corporation" => "Newfoundland and Labrador Housing Corporation",
            "Newfoundland and Labrador Hydro" => "Newfoundland and Labrador Hydro",
            "Newfoundland and Labrador Medical Care Plan - MCP" => "Newfoundland and Labrador Medical Care Plan - MCP",
            "Office of the Auditor General" => "Office of the Auditor General",
            "Office of the Chief Electoral Officer" => "Office of the Chief Electoral Officer",
            "Office of the Child and Youth Advocate" => "Office of the Child and Youth Advocate",
            "Office of the Citizens' Representative" => "Office of the Citizens' Representative",
            "Office of the Information and Privacy Commissioner" => "Office of the Information and Privacy Commissioner",
            "Provincial Information and Library Resources Board" => "Provincial Information and Library Resources Board",
            "Public Service Commission" => "Public Service Commission",
            "Research & Development Corporation" => "Research & Development Corporation",
            "Royal Newfoundland Constabulary" => "Royal Newfoundland Constabulary",
            "Service NL" => "Service NL",
            "Transportation and Works" => "Transportation and Works",
            "Workplace Health Safety and Compensation Commission" => "Workplace Health Safety and Compensation Commission",
            "Workplace Health, Safety and Compensation Review Division" => "Workplace Health, Safety and Compensation Review Division");

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'newfoundland-and-labrador',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $newfoundland_ministries),
            ));

            echo "</div>";
            echo "<label for='northwest-territories' class='basic-profile-label ministry northwest-territories'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field northwest-territories">';

            $northwest_territories_ministries = array();
            if (get_current_language() == 'en'){
                $northwest_territories_ministries = array("Aboriginal Affairs and Intergovernmental Relations" => "Aboriginal Affairs and Intergovernmental Relations",
                "Education, Culture and Employment" => "Education, Culture and Employment",
                "Environment and Natural Resources" => "Environment and Natural Resources",
                "Executive" => "Executive",
                "Finance" => "Finance",
                "Health and Social Services" => "Health and Social Services",
                "Human Resources" => "Human Resources",
                "Industry, Tourism and Investment" => "Industry, Tourism and Investment",
                "Justice" => "Justice",
                "Lands" => "Lands",
                "Legislative Assembly" => "Legislative Assembly",
                "Municipal and Community Affairs" => "Municipal and Community Affairs",
                "Public Works & Services" => "Public Works & Services",
                "Transportation" => "Transportation");
            } else {
                $northwest_territories_ministries = array("Aboriginal Affairs and Intergovernmental Relations" => "Ministère des Affaires autochtones et des Relations intergouvernementales",
                "Education, Culture and Employment" => "Ministère de l’Éducation, de la Culture et de la Formation",
                "Environment and Natural Resources" => "Ministère de l’Environnement et des Ressources naturelles",
                "Executive" => "Ministère de l’Exécutif",
                "Finance" => "Ministère des Finances",
                "Health and Social Services" => "Ministère de la Santé et des Services sociaux",
                "Human Resources" => "Ministère des Ressources humaines",
                "Industry, Tourism and Investment" => "Ministère de l’Industrie, du Tourisme et de l’Investissement",
                "Justice" => "Ministère de la Justice",
                "Lands" => "Ministère de l’Administration des terres",
                "Legislative Assembly" => "Assemblée législative des Territoires du Nord-Ouest",
                "Municipal and Community Affairs" => "Ministère des Affaires municipales et communautaires",
                "Public Works & Services" => "Ministère des Travaux publics et des Services",
                "Transportation" => "Ministère des Transports");
            }

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'northwest-territories',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $northwest_territories_ministries),
            ));

            echo "</div>";
            echo "<label for='nova-scotia' class='basic-profile-label ministry nova-scotia'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field nova-scotia">';

            $nova_scotia_ministries = array("Aboriginal Affairs" => "Aboriginal Affairs",
            "Acadian Affairs" => "Acadian Affairs",
            "African Nova Scotian Affairs" => "African Nova Scotian Affairs",
            "Agriculture" => "Agriculture",
            "Business" => "Business",
            "Communications Nova Scotia" => "Communications Nova Scotia",
            "Communities, Culture and Heritage" => "Communities, Culture and Heritage",
            "Community Services" => "Community Services",
            "Education and Early Childhood Development" => "Education and Early Childhood Development",
            "Energy" => "Energy",
            "Environment" => "Environment",
            "Executive Council Office" => "Executive Council Office",
            "Finance and Treasury Board" => "Finance and Treasury Board",
            "Fisheries and Aquaculture" => "Fisheries and Aquaculture",
            "Gaelic Affairs" => "Gaelic Affairs",
            "Health and Wellness" => "Health and Wellness",
            "Immigration" => "Immigration",
            "Intergovernmental Affairs" => "Intergovernmental Affairs",
            "Internal Services" => "Internal Services",
            "Justice" => "Justice",
            "Labour and Advanced Education" => "Labour and Advanced Education",
            "Municipal Affairs" => "Municipal Affairs",
            "Natural Resources" => "Natural Resources",
            "Public Service Commission" => "Public Service Commission",
            "Seniors" => "Seniors",
            "Service Nova Scotia" => "Service Nova Scotia",
            "Transportation and Infrastructure Renewal" => "Transportation and Infrastructure Renewal");

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'nova-scotia',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $nova_scotia_ministries),
            ));

            echo "</div>";
            echo "<label for='nunavut' class='basic-profile-label ministry nunavut'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field nunavut">';

            $nunavut_ministries = array();
            if (get_current_language() == 'en'){
                $nunavut_ministries = array("Community and Government Services" => "Community and Government Services",
                "Culture and Heritage" => "Culture and Heritage",
                "Economic Development and Transportation" => "Economic Development and Transportation",
                "Environment" => "Environment",
                "Education" => "Education",
                "Executive and Intergovernmental Affairs" => "Executive and Intergovernmental Affairs",
                "Family Services" => "Family Services",
                "Finance" => "Finance",
                "Health" => "Health",
                "Justice" => "Justice");
            } else {
                $nunavut_ministries = array("Culture and Heritage" => "Culture et Patrimoine",
                "Economic Development and Transportation" => "Développement économique et Transports",
                "Education" => "Éducation",
                "Environment" => "Environnement",
                "Executive and Intergovernmental Affairs" => "Exécutif et Affaires intergouvernementales",
                "Finance" => "Finances",
                "Justice" => "Justice",
                "Health" => "Santé",
                "Family Services" => "Services à la famille",
                "Community and Government Services" => "Services communautaires et gouvernementaux");
            }

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'nunavut',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $nunavut_ministries),
            ));

            echo "</div>";
            echo "<label for='ontario' class='basic-profile-label ministry ontario'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field ontario">';

            $ontario_ministries = array();
            if (get_current_language() == 'en'){
                $ontario_ministries = array("Accessibility Directorate of Ontario" => "Accessibility Directorate of Ontario",
                "Advanced Education and Skills Development" => "Advanced Education and Skills Development",
                "Agriculture, Food and Rural Affairs" => "Agriculture, Food and Rural Affairs",
                "Attorney General" => "Attorney General",
                "Children and Youth Services" => "Children and Youth Services",
                "Citizenship and Immigration" => "Citizenship and Immigration",
                "Community and Social Services" => "Community and Social Services",
                "Community Safety and Correctional Services" => "Community Safety and Correctional Services",
                "Economic Development and Growth" => "Economic Development and Growth",
                "Education" => "Education",
                "Energy" => "Energy",
                "Environment and Climate Change" => "Environment and Climate Change",
                "Finance" => "Finance",
                "Francophone Affairs" => "Francophone Affairs",
                "Government and Consumer Services" => "Government and Consumer Services",
                "Health and Long-Term Care" => "Health and Long-Term Care",
                "Housing" => "Housing",
                "Infrastructure" => "Infrastructure",
                "International Trade" => "International Trade",
                "Labour" => "Labour",
                "Municipal Affairs" => "Municipal Affairs",
                "Natural Resources and Forestry" => "Natural Resources and Forestry",
                "Northern Development and Mines" => "Northern Development and Mines",
                "Research, Innovation and Science" => "Research, Innovation and Science",
                "Seniors" => "Seniors",
                "Tourism, Culture and Sport" => "Tourism, Culture and Sport",
                "Transportation" => "Transportation",
                "Treasury Board Secretariat" => "Treasury Board Secretariat");
            } else {
                $ontario_ministries = array("Seniors" => "Affaires des personnes âgées",
                "Francophone Affairs" => "Affaires francophones",
                "International Trade" => "Commerce international",
                "Accessibility Directorate of Ontario" => "Direction générale de l'accessibilité",
                "Advanced Education and Skills Development" => "L’Enseignement supérieur et de la formation professionnelle",
                "Research, Innovation and Science" => "La Recherche, de l’innovation et des sciences",
                "Infrastructure" => "Ministère de l’infrastructure",
                "Health and Long-Term Care" => "Ministère de la santé et des soins de longue durée",
                "Community Safety and Correctional Services" => "Ministère de la sécurité communautaire et des services correctionnels",
                "Agriculture, Food and Rural Affairs" => "Ministère de l'agriculture, de l'alimentation et des affaires rurales",
                "Education" => "Ministère de l'éducation",
                "Energy" => "Ministère de l'énergie",
                "Environment and Climate Change" => "Ministère de l'environnement et de l'action en matière de changement climatique",
                "Citizenship and Immigration" => "Ministère des affaires civiques et de l'immigration",
                "Municipal Affairs" => "Ministère des affaires municipales",
                "Finance" => "Ministère des finances",
                "Natural Resources and Forestry" => "Ministère des richesses naturelles et des forêts",
                "Children and Youth Services" => "Ministère des services à l'enfance et à la jeunesse",
                "Government and Consumer Services" => "Ministère des services gouvernementaux et des services aux consommateurs",
                "Community and Social Services" => "Ministère des services sociaux et communautaires",
                "Transportation" => "Ministère des transports",
                "Northern Development and Mines" => "Ministère du développement du nord et des mines",
                "Economic Development and Growth" => "Ministère du développement économique et de la croissance",
                "Housing" => "Ministère du logement",
                "Attorney General" => "Ministère du procureur général",
                "Tourism, Culture and Sport" => "Ministère du tourisme, de la culture et du sport",
                "Labour" => "Ministère du travail",
                "Treasury Board Secretariat" => "Secrétariat du conseil du trésor");
            }

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'ontario',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $ontario_ministries),
            ));

            echo "</div>";
            echo "<label for='prince-edward-island' class='basic-profile-label ministry prince-edward-island'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field prince-edward-island">';

            $pei_ministries = array();
            if (get_current_language() == 'en'){
                $pei_ministries = array("Agriculture and Fisheries" => "Agriculture and Fisheries",
                "Communities, Land and Environment" => "Communities, Land and Environment",
                "Economic Development and Tourism" => "Economic Development and Tourism",
                "Education, Early Learning and Culture" => "Education, Early Learning and Culture",
                "Family and Human Services" => "Family and Human Services",
                "Finance" => "Finance",
                "Health and Wellness" => "Health and Wellness",
                "Justice and Public Safety" => "Justice and Public Safety",
                "Transportation, Infrastructure and Energy" => "Transportation, Infrastructure and Energy",
                "Workforce and Advanced Learning" => "Workforce and Advanced Learning");
            } else {
                $pei_ministries = array("Agriculture and Fisheries" => "Agriculture et Pêches",
                "Communities, Land and Environment" => "Communautés, Terres et Environnement",
                "Economic Development and Tourism" => "Développement économique et Tourisme",
                "Education, Early Learning and Culture" => "Éducation, Développement préscolaire et Culture",
                "Finance" => "Finances",
                "Justice and Public Safety" => "Justice et Sécurité publique",
                "Workforce and Advanced Learning" => "Main-d’œuvre et Études supérieures",
                "Health and Wellness" => "Santé et Mieux-être",
                "Family and Human Services" => "Services à la famille et à la personne",
                "Transportation, Infrastructure and Energy" => "Transports, Infrastructure et Énergie");
            }

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'prince-edward-island',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $pei_ministries),
            ));

            echo "</div>";
            echo "<label for='quebec' class='basic-profile-label ministry quebec'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field quebec">';

            $quebec_ministries = array("Agence de la santé et des services sociaux de Chaudière-Appalaches" => "Agence de la santé et des services sociaux de Chaudière-Appalaches",
            "Agence de la santé et des services sociaux de la Capitale-Nationale" => "Agence de la santé et des services sociaux de la Capitale-Nationale",
            "Agence de la santé et des services sociaux de la Côte-Nord" => "Agence de la santé et des services sociaux de la Côte-Nord",
            "Agence de la santé et des services sociaux de la Gaspésie-Iles-de-la-Madeleine" => "Agence de la santé et des services sociaux de la Gaspésie-Iles-de-la-Madeleine",
            "Agence de la santé et des services sociaux de la Mauricie et du Centre-du-Québecc" => "Agence de la santé et des services sociaux de la Mauricie et du Centre-du-Québecc",
            "Agence de la santé et des services sociaux de l'Abitibi-Témiscamingue" => "Agence de la santé et des services sociaux de l'Abitibi-Témiscamingue",
            "Agence de la santé et des services sociaux de Lanaudière" => "Agence de la santé et des services sociaux de Lanaudière",
            "Agence de la santé et des services sociaux de Laval" => "Agence de la santé et des services sociaux de Laval",
            "Agence de la santé et des services sociaux de l'Estrie" => "Agence de la santé et des services sociaux de l'Estrie",
            "Agence de la santé et des services sociaux de l'Outaouais" => "Agence de la santé et des services sociaux de l'Outaouais",
            "Agence de la santé et des services sociaux de Montréal" => "Agence de la santé et des services sociaux de Montréal",
            "Agence de la santé et des services sociaux des Laurentides" => "Agence de la santé et des services sociaux des Laurentides",
            "Agence de la santé et des services sociaux du Bas-Saint-Laurent" => "Agence de la santé et des services sociaux du Bas-Saint-Laurent",
            "Agence de la santé et des services sociaux du Saguenay–Lac-Saint-Jean" => "Agence de la santé et des services sociaux du Saguenay–Lac-Saint-Jean",
            "Agence métropolitaine de transport" => "Agence métropolitaine de transport",
            "Aide financière aux études" => "Aide financière aux études",
            "Assemblée nationale du Québec" => "Assemblée nationale du Québec",
            "Autorité des marchés financiers" => "Autorité des marchés financiers",
            "Bibliothèque et Archives nationales du Québec" => "Bibliothèque et Archives nationales du Québec",
            "Bureau d'audiences publiques sur l'environnement" => "Bureau d'audiences publiques sur l'environnement",
            "Bureau de normalisation du Québec" => "Bureau de normalisation du Québec",
            "Bureau des infractions et amendes" => "Bureau des infractions et amendes",
            "Bureau du coroner" => "Bureau du coroner",
            "Bureau du forestier en chef" => "Bureau du forestier en chef",
            "Caisse de dépôt et placement du Québec" => "Caisse de dépôt et placement du Québec",
            "Centre de Conservation du Québec" => "Centre de Conservation du Québec",
            "Centre de gestion de l'équipement roulant" => "Centre de gestion de l'équipement roulant",
            "Centre de la francophonie des Amériques" => "Centre de la francophonie des Amériques",
            "Centre de recherche industrielle du Québec" => "Centre de recherche industrielle du Québec",
            "Centre de services partagés du Québec" => "Centre de services partagés du Québec",
            "Centre de toxicologie du Québec" => "Centre de toxicologie du Québec",
            "Centre d'étude sur la pauvreté et l'exclusion sociale" => "Centre d'étude sur la pauvreté et l'exclusion sociale",
            "Centre d'expertise des grands organismes" => "Centre d'expertise des grands organismes",
            "Centre d'expertise hydrique du Québec" => "Centre d'expertise hydrique du Québec",
            "Centre intégré de santé et de services sociaux de la Montérégie-Centre" => "Centre intégré de santé et de services sociaux de la Montérégie-Centre",
            "Centre intégré de santé et de services sociaux de la Montérégie–Centre" => "Centre intégré de santé et de services sociaux de la Montérégie–Centre",
            "Centre intégré de santé et de services sociaux de la Montérégie-Ouest" => "Centre intégré de santé et de services sociaux de la Montérégie-Ouest",
            "Centre intégré de santé et de services sociaux des Îles" => "Centre intégré de santé et de services sociaux des Îles",
            "Centre intégré universitaire de santé et de services sociaux du Nord-de-l'Île-de-Montréal" => "Centre intégré universitaire de santé et de services sociaux du Nord-de-l'Île-de-Montréal",
            "Centre intégré universitaire de santé et services sociaux de l'Est-de-l'Île-de-Montréal" => "Centre intégré universitaire de santé et services sociaux de l'Est-de-l'Île-de-Montréal",
            "Centre intégré universitaire du Centre-Est-de-l'Île-de-Montréal" => "Centre intégré universitaire du Centre-Est-de-l'Île-de-Montréal",
            "Centre intégré universitaire du Centre-Ouest-de-l'Île-de-Montréal" => "Centre intégré universitaire du Centre-Ouest-de-l'Île-de-Montréal",
            "Centre local de services communautaires" => "Centre local de services communautaires",
            "Centre régional de santé et de services sociaux de la Baie-James" => "Centre régional de santé et de services sociaux de la Baie-James",
            "Comité consultatif du travail et de la main-d'œuvre" => "Comité consultatif du travail et de la main-d'œuvre",
            "Comité consultatif sur l'accessibilité financière aux études" => "Comité consultatif sur l'accessibilité financière aux études",
            "Comité de déontologie policière" => "Comité de déontologie policière",
            "Comité pour la prestation des services de santé et des services sociaux aux personnes issues des communautés ethnoculturelles" => "Comité pour la prestation des services de santé et des services sociaux aux personnes issues des communautés ethnoculturelles",
            "Commissaire à la déontologie policière" => "Commissaire à la déontologie policière",
            "Commissaire à la lutte contre la corruption" => "Commissaire à la lutte contre la corruption",
            "Commissaire à la santé et au bien-être" => "Commissaire à la santé et au bien-être",
            "Commissaire à l'éthique et à la déontologie" => "Commissaire à l'éthique et à la déontologie",
            "Commissaire au lobbyisme" => "Commissaire au lobbyisme",
            "Commission consultative de l'enseignement privé" => "Commission consultative de l'enseignement privé",
            "Commission d'accès à l'information" => "Commission d'accès à l'information",
            "Commission de la capitale nationale du Québec" => "Commission de la capitale nationale du Québec",
            "Commission de la construction du Québec" => "Commission de la construction du Québec",
            "Commission de la fonction publique" => "Commission de la fonction publique",
            "Commission de la qualité de l'environnement Kativik" => "Commission de la qualité de l'environnement Kativik",
            "Commission de la représentation électorale" => "Commission de la représentation électorale",
            "Commission de l'éducation en langue anglaise" => "Commission de l'éducation en langue anglaise",
            "Commission de l'éthique de la science et de la technologie" => "Commission de l'éthique de la science et de la technologie",
            "Commission de protection du territoire agricole du Québec" => "Commission de protection du territoire agricole du Québec",
            "Commission de toponymie" => "Commission de toponymie",
            "Commission d'enquête sur l’octroi et la gestion des contrats publics dans l’industrie de la construction" => "Commission d'enquête sur l’octroi et la gestion des contrats publics dans l’industrie de la construction",
            "Commission des droits de la personne et des droits de la jeunesse" => "Commission des droits de la personne et des droits de la jeunesse",
            "Commission des normes, de l'équité, de la santé et de la sécurité du travail" => "Commission des normes, de l'équité, de la santé et de la sécurité du travail",
            "Commission des partenaires du marché du travail" => "Commission des partenaires du marché du travail",
            "Commission des services juridiques" => "Commission des services juridiques",
            "Commission des transports du Québec" => "Commission des transports du Québec",
            "Commission des valeurs mobilières du Québec (voir Autorité des marchés financiers)" => "Commission des valeurs mobilières du Québec (voir Autorité des marchés financiers)",
            "Commission d'évaluation de l'enseignement collégial" => "Commission d'évaluation de l'enseignement collégial",
            "Commission municipale du Québec" => "Commission municipale du Québec",
            "Commission québécoise des libérations conditionnelles" => "Commission québécoise des libérations conditionnelles",
            "Conseil consultatif de la lecture et du livre" => "Conseil consultatif de la lecture et du livre",
            "Conseil cri de la santé et des services sociaux de la Baie James" => "Conseil cri de la santé et des services sociaux de la Baie James",
            "Conseil de gestion de l'assurance parentale" => "Conseil de gestion de l'assurance parentale",
            "Conseil de la justice administrative" => "Conseil de la justice administrative",
            "Conseil de la magistrature du Québec" => "Conseil de la magistrature du Québec",
            "Conseil de l'Ordre du Québec" => "Conseil de l'Ordre du Québec",
            "Conseil des appellations réservées et des termes valorisants" => "Conseil des appellations réservées et des termes valorisants",
            "Conseil des arts et des lettres du Québec" => "Conseil des arts et des lettres du Québec",
            "Conseil du statut de la femme" => "Conseil du statut de la femme",
            "Conseil supérieur de la langue française" => "Conseil supérieur de la langue française",
            "Conseil supérieur de l'éducation" => "Conseil supérieur de l'éducation",
            "Conseils régionaux des partenaires du marché du travail" => "Conseils régionaux des partenaires du marché du travail",
            "Conservatoire de musique et d'art dramatique du Québec" => "Conservatoire de musique et d'art dramatique du Québec",
            "Corporation d'urgence-santé" => "Corporation d'urgence-santé",
            "Cour d'appel du Québec" => "Cour d'appel du Québec",
            "Cour du Québec" => "Cour du Québec",
            "Cour supérieure du Québec" => "Cour supérieure du Québec",
            "Curateur public du Québec" => "Curateur public du Québec",
            "Directeur de l'état civil" => "Directeur de l'état civil",
            "Directeur des poursuites criminelles et pénales" => "Directeur des poursuites criminelles et pénales",
            "Directeur général des élections du Québec" => "Directeur général des élections du Québec",
            "École nationale de police du Québec" => "École nationale de police du Québec",
            "École nationale des pompiers du Québec" => "École nationale des pompiers du Québec",
            "Emploi-Québec" => "Emploi-Québec",
            "Épargne Placements Québec" => "Épargne Placements Québec",
            "Financière agricole du Québec" => "Financière agricole du Québec",
            "Fondation de la faune du Québec" => "Fondation de la faune du Québec",
            "Fonds d'aide aux recours collectifs" => "Fonds d'aide aux recours collectifs",
            "Fonds de la recherche en santé du Québec" => "Fonds de la recherche en santé du Québec",
            "Fonds de recherche du Québec – Scientifique en chef" => "Fonds de recherche du Québec – Scientifique en chef",
            "Fonds québécois de la recherche sur la nature et les technologies" => "Fonds québécois de la recherche sur la nature et les technologies",
            "Fonds québécois de la recherche sur la société et la culture" => "Fonds québécois de la recherche sur la société et la culture",
            "Héma-Québec" => "Héma-Québec",
            "Hydro-Québec" => "Hydro-Québec",
            "Indemnisation des victimes d’actes criminels" => "Indemnisation des victimes d’actes criminels",
            "Institut de la statistique du Québec" => "Institut de la statistique du Québec",
            "Institut de tourisme et d'hôtellerie du Québec" => "Institut de tourisme et d'hôtellerie du Québec",
            "Institut national de santé publique du Québec" => "Institut national de santé publique du Québec",
            "Institut national des mines" => "Institut national des mines",
            "Institut national d'excellence en santé et en services sociaux" => "Institut national d'excellence en santé et en services sociaux",
            "Investissement Québec" => "Investissement Québec",
            "La Financière agricole du Québec - Développement international" => "La Financière agricole du Québec - Développement international",
            "Les Publications du Québec" => "Les Publications du Québec",
            "Ministère de l’Éducation et de l’Enseignement supérieur" => "Ministère de l’Éducation et de l’Enseignement supérieur",
            "Ministère de la Culture et des Communications" => "Ministère de la Culture et des Communications",
            "Ministère de la Famille" => "Ministère de la Famille",
            "Ministère de la Justice" => "Ministère de la Justice",
            "Ministère de la Santé et des Services sociaux" => "Ministère de la Santé et des Services sociaux",
            "Ministère de la Sécurité publique" => "Ministère de la Sécurité publique",
            "Ministère de l'Agriculture, des Pêcheries et de l'Alimentation" => "Ministère de l'Agriculture, des Pêcheries et de l'Alimentation",
            "Ministère de l'Économie, de la Science et de l'Innovation" => "Ministère de l'Économie, de la Science et de l'Innovation",
            "Ministère de l'Énergie et des Ressources naturelles" => "Ministère de l'Énergie et des Ressources naturelles",
            "Ministère de l'Immigration, de la Diversité et de l'Inclusion" => "Ministère de l'Immigration, de la Diversité et de l'Inclusion",
            "Ministère des Affaires municipales et de l'Occupation du territoire" => "Ministère des Affaires municipales et de l'Occupation du territoire",
            "Ministère des Finances" => "Ministère des Finances",
            "Ministère des Forêts, de la Faune et des Parcs" => "Ministère des Forêts, de la Faune et des Parcs",
            "Ministère des Relations internationales et de la Francophonie" => "Ministère des Relations internationales et de la Francophonie",
            "Ministère des Transports, de la Mobilité durable et de l'Électrification des transports" => "Ministère des Transports, de la Mobilité durable et de l'Électrification des transports",
            "Ministère du Conseil exécutif" => "Ministère du Conseil exécutif",
            "Ministère du Développement durable, de l'Environnement et de la Lutte contre les changements climatiques" => "Ministère du Développement durable, de l'Environnement et de la Lutte contre les changements climatiques",
            "Ministère du Tourisme" => "Ministère du Tourisme",
            "Ministère du Travail, de l'Emploi et de la Solidarité sociale" => "Ministère du Travail, de l'Emploi et de la Solidarité sociale",
            "Musée d'art contemporain de Montréal" => "Musée d'art contemporain de Montréal",
            "Musée de la civilisation" => "Musée de la civilisation",
            "Musée de la Place royale" => "Musée de la Place royale",
            "Musée de l'Amérique francophone" => "Musée de l'Amérique francophone",
            "Musée national des beaux-arts du Québec" => "Musée national des beaux-arts du Québec",
            "Office de la protection du consommateur" => "Office de la protection du consommateur",
            "Office de la Sécurité du revenu des chasseurs et piégeurs cris" => "Office de la Sécurité du revenu des chasseurs et piégeurs cris",
            "Office des personnes handicapées du Québec" => "Office des personnes handicapées du Québec",
            "Office des professions du Québec" => "Office des professions du Québec",
            "Office franco-québécois pour la jeunesse" => "Office franco-québécois pour la jeunesse",
            "Office Québec-Monde pour la jeunesse" => "Office Québec-Monde pour la jeunesse",
            "Office québécois de la langue française" => "Office québécois de la langue française",
            "Palais des congrès de Montréal" => "Palais des congrès de Montréal",
            "Protecteur du citoyen" => "Protecteur du citoyen",
            "RECYC-QUÉBEC" => "RECYC-QUÉBEC",
            "Régie de l'assurance maladie du Québec" => "Régie de l'assurance maladie du Québec",
            "Régie de l'assurance-dépôts du Québec (voir Autorité des marchés financiers)" => "Régie de l'assurance-dépôts du Québec (voir Autorité des marchés financiers)",
            "Régie de l'énergie" => "Régie de l'énergie",
            "Régie des alcools, des courses et des jeux" => "Régie des alcools, des courses et des jeux",
            "Régie des installations olympiques" => "Régie des installations olympiques",
            "Régie des marchés agricoles et alimentaires du Québec" => "Régie des marchés agricoles et alimentaires du Québec",
            "Régie du bâtiment du Québec" => "Régie du bâtiment du Québec",
            "Régie du Cinéma" => "Régie du Cinéma",
            "Régie du logement" => "Régie du logement",
            "Registraire des entreprises" => "Registraire des entreprises",
            "Registre des droits personnels et réels mobiliers" => "Registre des droits personnels et réels mobiliers",
            "Registre des lobbyistes" => "Registre des lobbyistes",
            "Registre foncier du Québec" => "Registre foncier du Québec",
            "Retraite Québec" => "Retraite Québec",
            "Revenu Québec" => "Revenu Québec",
            "Secrétariat à la condition féminine" => "Secrétariat à la condition féminine",
            "Secrétariat à la jeunesse" => "Secrétariat à la jeunesse",
            "Secrétariat à la politique linguistique" => "Secrétariat à la politique linguistique",
            "Secrétariat à l'accès à l'information et à la réforme des institutions démocratiques" => "Secrétariat à l'accès à l'information et à la réforme des institutions démocratiques",
            "Secrétariat aux affaires autochtones" => "Secrétariat aux affaires autochtones",
            "Secrétariat aux affaires intergouvernementales canadiennes" => "Secrétariat aux affaires intergouvernementales canadiennes",
            "Secrétariat aux aînés" => "Secrétariat aux aînés",
            "Secrétariat de l'Ordre national du Québec" => "Secrétariat de l'Ordre national du Québec",
            "Secrétariat du Conseil du trésor" => "Secrétariat du Conseil du trésor",
            "Secrétariat du travail" => "Secrétariat du travail",
            "Société de développement de la Baie-James" => "Société de développement de la Baie-James",
            "Société de développement des entreprises culturelles" => "Société de développement des entreprises culturelles",
            "Société de financement des infrastructures locales du Québec" => "Société de financement des infrastructures locales du Québec",
            "Société de la Place des Arts" => "Société de la Place des Arts",
            "Société de l'assurance automobile du Québec" => "Société de l'assurance automobile du Québec",
            "Société de télédiffusion du Québec (Télé-Québec)" => "Société de télédiffusion du Québec (Télé-Québec)",
            "Société des alcools du Québec" => "Société des alcools du Québec",
            "Société des établissements de plein air du Québec" => "Société des établissements de plein air du Québec",
            "Société des loteries du Québec (Loto-Québec)" => "Société des loteries du Québec (Loto-Québec)",
            "Société des traversiers du Québec" => "Société des traversiers du Québec",
            "Société d'habitation du Québec" => "Société d'habitation du Québec",
            "Société du Centre des congrès de Québec" => "Société du Centre des congrès de Québec",
            "Société du Grand Théâtre de Québec" => "Société du Grand Théâtre de Québec",
            "Société du Palais des congrès de Montréal" => "Société du Palais des congrès de Montréal",
            "Société du parc industriel et portuaire de Bécancour" => "Société du parc industriel et portuaire de Bécancour",
            "Société du Plan Nord" => "Société du Plan Nord",
            "Société québécoise des infrastructures" => "Société québécoise des infrastructures",
            "Société québécoise d'information juridique" => "Société québécoise d'information juridique",
            "Tribunal administratif des marchés financiers" => "Tribunal administratif des marchés financiers",
            "Tribunal administratif du Québec" => "Tribunal administratif du Québec",
            "Tribunal administratif du travail" => "Tribunal administratif du travail",
            "Tribunal des droits de la personne" => "Tribunal des droits de la personne",
            "Vérificateur général du Québec" => "Vérificateur général du Québec");

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'quebec',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $quebec_ministries),
            ));

            echo "</div>";
            echo "<label for='saskatchewan' class='basic-profile-label ministry saskatchewan'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field saskatchewan">';

            $saskatchewan_ministries = array("Advanced Education" => "Advanced Education",
            "Agriculture" => "Agriculture",
            "Central Services" => "Central Services",
            "Economy" => "Economy",
            "Education" => "Education",
            "Energy and Resources" => "Energy and Resources",
            "Environment" => "Environment",
            "Finance" => "Finance",
            "Government Relations" => "Government Relations",
            "Health" => "Health",
            "Highways and Infrastructure" => "Highways and Infrastructure",
            "Justice" => "Justice",
            "Labour Relations and Workplace Safety" => "Labour Relations and Workplace Safety",
            "Parks, Culture and Sport" => "Parks, Culture and Sport",
            "Social Services" => "Social Services");

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'saskatchewan',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $saskatchewan_ministries),
            ));

            echo "</div>";
            echo "<label for='yukon' class='basic-profile-label ministry yukon'>" . elgg_echo("gcconnex_profile:basic:ministry")."</label>";
            echo '<div class="basic-profile-field yukon">';

            $yukon_ministries = array("Community Services" => "Community Services",
            "Economic Development" => "Economic Development",
            "Education" => "Education",
            "Energy, Mines and Resources" => "Energy, Mines and Resources",
            "Environment" => "Environment",
            "Executive Council Office" => "Executive Council Office",
            "Finance" => "Finance",
            "French Language Services Directorate" => "French Language Services Directorate",
            "Health and Social Services" => "Health and Social Services",
            "Highways and Public Works" => "Highways and Public Works",
            "Justice" => "Justice",
            "Public Service Commission" => "Public Service Commission",
            "Tourism and Culture" => "Tourism and Culture",
            "Women's Directorate" => "Women's Directorate");

            echo elgg_view('input/select', array(
                'name' => 'ministry',
                'id' => 'yukon',
                'class' => 'form-control ministry',
                'options_values' => array_merge(array('default_invalid_value' => elgg_echo('gcRegister:make_selection')), $yukon_ministries),
            ));

        } else if (strcmp($field, 'institution') == 0) {

            echo "<label for='{$field}' class='basic-profile-label {$field}-label'>" . elgg_echo("gcconnex_profile:basic:{$field}")."</label>";
            echo '<div class="basic-profile-field user_type-test">';

            /*
            // re-use the data that we've already put into the domain module
            $query = "SELECT ext, dept FROM email_extensions WHERE dept LIKE '%University%' OR dept LIKE '%College%' OR dept LIKE '%Institute%' OR dept LIKE '%Université%' OR dept LIKE '%Cégep%' OR dept LIKE '%Institut%'";
            $universities = get_data($query);
            $university_list = array();
            // this is bad programming but... reconstructing the array
            foreach ($universities as $university) {
                $university_list[$university->ext] = $university->dept;
            }

            echo elgg_view('input/select', array(
                'name' => $field,
                'id' => $field,
                'class' => "gcconnex-basic-{$field}",
                'value' => $value,
                'options_values' => $university_list, 
            ));
            */

            $institution_list = array("university" => "University", "college" => "College");

            echo elgg_view('input/select', array(
                'name' => $field,
                'id' => $field,
                'class' => "gcconnex-basic-{$field}",
                'value' => $value,
                'options_values' => $institution_list, 
            ));

        } else if (strcmp($field, 'university') == 0) {

            echo "<label for='{$field}' class='basic-profile-label {$field}-label'>" . elgg_echo("gcconnex_profile:basic:{$field}")."</label>";
            echo '<div class="basic-profile-field user_type-test">';

            $universities = array("Acadia University" => "Acadia University",
            "Algoma University" => "Algoma University",
            "Athabasca University" => "Athabasca University",
            "Bishop's University" => "Bishop's University",
            "Brandon University" => "Brandon University",
            "Brescia University College" => "Brescia University College",
            "Brock University" => "Brock University",
            "Campion College" => "Campion College",
            "Canada Mennonite University" => "Canada Mennonite University",
            "Cape Breton University" => "Cape Breton University",
            "Carleton University" => "Carleton University",
            "Concordia University" => "Concordia University",
            "Concordia University of Edmonton" => "Concordia University of Edmonton",
            "Dalhousie University" => "Dalhousie University",
            "Dominican University College" => "Dominican University College",
            "École de technolgie supérieure" => "École de technolgie supérieure",
            "École des Hautes Études Commerciales de Montréal (HEC Montréal)" => "École des Hautes Études Commerciales de Montréal (HEC Montréal)",
            "École nationale d'administration publique (ENAP)" => "École nationale d'administration publique (ENAP)",
            "École Polytechnique de Montréal" => "École Polytechnique de Montréal",
            "Emily Carr University of Art and Design" => "Emily Carr University of Art and Design",
            "First Nations University of Canada" => "First Nations University of Canada",
            "Glendon College (York University)" => "Glendon College (York University)",
            "Huron University College" => "Huron University College",
            "Institut national de la recherche scientifique" => "Institut national de la recherche scientifique",
            "King's University College" => "King's University College",
            "Kwantlen Polytechnic University" => "Kwantlen Polytechnic University",
            "Lakehead University" => "Lakehead University",
            "Laurentian University" => "Laurentian University",
            "Luther College" => "Luther College",
            "MacEwan University" => "MacEwan University",
            "McGill University" => "McGill University",
            "McMaster University" => "McMaster University",
            "Memorial University" => "Memorial University",
            "Mount Allison University" => "Mount Allison University",
            "Mount Royal University" => "Mount Royal University",
            "Mount Saint Vincent University" => "Mount Saint Vincent University",
            "Nipissing University" => "Nipissing University",
            "Nova Scotia College of Art and Design University" => "Nova Scotia College of Art and Design University",
            "Ontario College of Art and Design University" => "Ontario College of Art and Design University",
            "Queen's University" => "Queen's University",
            "Redeemer University College" => "Redeemer University College",
            "Royal Military College" => "Royal Military College",
            "Royal Roads University" => "Royal Roads University",
            "Ryerson University" => "Ryerson University",
            "Saint Mary's University" => "Saint Mary's University",
            "Saint Paul University" => "Saint Paul University",
            "Simon Fraser University" => "Simon Fraser University",
            "St. Francis Xavier University" => "St. Francis Xavier University",
            "St. Jerome's University" => "St. Jerome's University",
            "St. Paul's College" => "St. Paul's College",
            "St. Thomas More College" => "St. Thomas More College",
            "St. Thomas University" => "St. Thomas University",
            "Télé-université (TÉLUQ)" => "Télé-université (TÉLUQ)",
            "The King's University" => "The King's University",
            "Thompson Rivers University" => "Thompson Rivers University",
            "Trent University" => "Trent University",
            "Trinity Western University" => "Trinity Western University",
            "Université de Moncton" => "Université de Moncton",
            "Université de Montréal" => "Université de Montréal",
            "Université de Saint-Boniface" => "Université de Saint-Boniface",
            "Université de Sherbrooke" => "Université de Sherbrooke",
            "Université du Québec" => "Université du Québec",
            "Université du Québec à Chicoutimi" => "Université du Québec à Chicoutimi",
            "Université du Québec à Montréal" => "Université du Québec à Montréal",
            "Université du Québec à Rimouski" => "Université du Québec à Rimouski",
            "Université du Québec à Trois‑Rivières" => "Université du Québec à Trois‑Rivières",
            "Université du Québec en Abitibi‑Témiscamingue" => "Université du Québec en Abitibi‑Témiscamingue",
            "Université du Québec en Outaouais" => "Université du Québec en Outaouais",
            "Université Laval" => "Université Laval",
            "Université Sainte‑Anne" => "Université Sainte‑Anne",
            "University of Alberta" => "University of Alberta",
            "University of British Columbia" => "University of British Columbia",
            "University of Calgary" => "University of Calgary",
            "University of Guelph" => "University of Guelph",
            "University of King's College" => "University of King's College",
            "University of Lethbridge" => "University of Lethbridge",
            "University of Manitoba" => "University of Manitoba",
            "University of New Brunswick" => "University of New Brunswick",
            "University of Northern British Columbia" => "University of Northern British Columbia",
            "University of Ontario Institute of Technology" => "University of Ontario Institute of Technology",
            "University of Ottawa" => "University of Ottawa",
            "University of Prince Edward Island" => "University of Prince Edward Island",
            "University of Regina" => "University of Regina",
            "University of Saskatchewan" => "University of Saskatchewan",
            "University of St. Michael's College" => "University of St. Michael's College",
            "University of Sudbury" => "University of Sudbury",
            "University of the Fraser Valley" => "University of the Fraser Valley",
            "University of Toronto" => "University of Toronto",
            "University of Trinity College" => "University of Trinity College",
            "University of Victoria" => "University of Victoria",
            "University of Waterloo" => "University of Waterloo",
            "University of Western Ontario" => "University of Western Ontario",
            "University of Windsor" => "University of Windsor",
            "University of Winnipeg" => "University of Winnipeg",
            "Vancouver Island University" => "Vancouver Island University",
            "Victoria University" => "Victoria University",
            "Wilfrid Laurier University" => "Wilfrid Laurier University",
            "York University" => "York University");

            echo elgg_view('input/select', array(
                'name' => $field,
                'id' => $field,
                'class' => "gcconnex-basic-{$field}",
                'value' => $value,
                'options_values' => $universities, 
            ));       

        } else if (strcmp($field, 'college') == 0) {

            echo "<label for='{$field}' class='basic-profile-label {$field}-label'>" . elgg_echo("gcconnex_profile:basic:{$field}")."</label>";
            echo '<div class="basic-profile-field user_type-test">';

            $colleges = array("Alberta College of Art and Design" => "Alberta College of Art and Design",
            "Algonquin College" => "Algonquin College",
            "Assiniboine Community College" => "Assiniboine Community College",
            "Aurora College" => "Aurora College",
            "Bow Valley College" => "Bow Valley College",
            "British Columbia Institute of Technology" => "British Columbia Institute of Technology",
            "Cambrian College of Applied Arts and Technology" => "Cambrian College of Applied Arts and Technology",
            "Camosun College" => "Camosun College",
            "Canadore College of Applied Arts and Technology" => "Canadore College of Applied Arts and Technology",
            "Capilano University" => "Capilano University",
            "Carlton Trail College" => "Carlton Trail College",
            "Cégep André-Laurendeau" => "Cégep André-Laurendeau",
            "Cégep de Chicoutimi" => "Cégep de Chicoutimi",
            "Cégep de Jonquière" => "Cégep de Jonquière",
            "Cégep de l’Abitibi-Témiscamingue" => "Cégep de l’Abitibi-Témiscamingue",
            "Cégep de la Gaspésie et des Îles" => "Cégep de la Gaspésie et des Îles",
            "Cégep de La Pocatière" => "Cégep de La Pocatière",
            "Cégep de Matane" => "Cégep de Matane",
            "Cégep de Rimouski" => "Cégep de Rimouski",
            "Cégep de Rivière-du-Loup" => "Cégep de Rivière-du-Loup",
            "Cégep de Sainte-Foy" => "Cégep de Sainte-Foy",
            "Cégep de Saint-Félicien" => "Cégep de Saint-Félicien",
            "Cégep de Saint-Laurent" => "Cégep de Saint-Laurent",
            "Cégep de Sept-Îles" => "Cégep de Sept-Îles",
            "Cégep de Sherbrooke" => "Cégep de Sherbrooke",
            "Cégep de Thetford" => "Cégep de Thetford",
            "Cégep de Trois-Rivières" => "Cégep de Trois-Rivières",
            "Cégep de Victoriaville" => "Cégep de Victoriaville",
            "Cégep Édouard-Montpetit" => "Cégep Édouard-Montpetit",
            "Cégep Garneau" => "Cégep Garneau",
            "Cégep Heritage College" => "Cégep Heritage College",
            "Cégep John Abbott College" => "Cégep John Abbott College",
            "Cégep Limoilou" => "Cégep Limoilou",
            "Cégep Marie-Victorin" => "Cégep Marie-Victorin",
            "Cégep régional de Lanaudière" => "Cégep régional de Lanaudière",
            "Cégep Saint-Jean-sur-Richelieu" => "Cégep Saint-Jean-sur-Richelieu",
            "Centennial College" => "Centennial College",
            "Centre for Nursing Studies" => "Centre for Nursing Studies",
            "Champlain Regional College" => "Champlain Regional College",
            "Collège Acadie Î.-P.-É." => "Collège Acadie Î.-P.-É.",
            "Collège André-Grasset" => "Collège André-Grasset",
            "Collège Boréal" => "Collège Boréal",
            "Collège communautaire du Nouveau-Brunswick" => "Collège communautaire du Nouveau-Brunswick",
            "Collège de Maisonneuve" => "Collège de Maisonneuve",
            "Collège Éducacentre" => "Collège Éducacentre",
            "Collège LaSalle" => "Collège LaSalle",
            "Collège Lionel-Groulx" => "Collège Lionel-Groulx",
            "Collège Mathieu" => "Collège Mathieu",
            "Collège Montmorency" => "Collège Montmorency",
            "Collège nordique francophone" => "Collège nordique francophone",
            "College of New Caledonia" => "College of New Caledonia",
            "College of the North Atlantic (CNA)" => "College of the North Atlantic (CNA)",
            "College of the Rockies" => "College of the Rockies",
            "Collège Shawinigan" => "Collège Shawinigan",
            "Conestoga College Institute of Technology and Advanced Learning" => "Conestoga College Institute of Technology and Advanced Learning",
            "Confederation College" => "Confederation College",
            "Cumberland College" => "Cumberland College",
            "Dalhousie Agricultural Campus of Dalhousie University" => "Dalhousie Agricultural Campus of Dalhousie University",
            "Douglas College" => "Douglas College",
            "Dumont Technical Institute" => "Dumont Technical Institute",
            "Durham College" => "Durham College",
            "École technique et professionnelle, Université de Saint-Boniface" => "École technique et professionnelle, Université de Saint-Boniface",
            "Emily Carr University of Art and Design" => "Emily Carr University of Art and Design",
            "Fanshawe College of Applied Arts and Technology" => "Fanshawe College of Applied Arts and Technology",
            "First Nations Technical Institute" => "First Nations Technical Institute",
            "Fleming College" => "Fleming College",
            "George Brown College" => "George Brown College",
            "Georgian College of Applied Arts and Technology" => "Georgian College of Applied Arts and Technology",
            "Grande Prairie Regional College" => "Grande Prairie Regional College",
            "Great Plains College" => "Great Plains College",
            "Holland College" => "Holland College",
            "Humber College Institute of Technology & Advanced Learning" => "Humber College Institute of Technology & Advanced Learning",
            "Institut de tourisme et d’hôtellerie du Québec" => "Institut de tourisme et d’hôtellerie du Québec",
            "Justice Institute of British Columbia" => "Justice Institute of British Columbia",
            "Kenjgewin Teg Educational Institute (KTEI)" => "Kenjgewin Teg Educational Institute (KTEI)",
            "Keyano College" => "Keyano College",
            "Kwantlen Polytechnic University" => "Kwantlen Polytechnic University",
            "La Cité" => "La Cité",
            "Lakeland College" => "Lakeland College",
            "Lambton College of Applied Arts and Technology" => "Lambton College of Applied Arts and Technology",
            "Langara College" => "Langara College",
            "Lethbridge College" => "Lethbridge College",
            "Loyalist College" => "Loyalist College",
            "Manitoba Institute of Trades and Technology" => "Manitoba Institute of Trades and Technology",
            "Marine Institute" => "Marine Institute",
            "Medicine Hat College" => "Medicine Hat College",
            "Michener Institute of Education at UHN" => "Michener Institute of Education at UHN",
            "Mohawk College" => "Mohawk College",
            "Native Education College" => "Native Education College",
            "New Brunswick College of Craft and Design" => "New Brunswick College of Craft and Design",
            "New Brunswick Community College" => "New Brunswick Community College",
            "Niagara College" => "Niagara College",
            "Nicola Valley Institute of Technology" => "Nicola Valley Institute of Technology",
            "NorQuest College" => "NorQuest College",
            "North Island College" => "North Island College",
            "North West College" => "North West College",
            "Northern Alberta Institute of Technology (NAIT)" => "Northern Alberta Institute of Technology (NAIT)",
            "Northern College" => "Northern College",
            "Northern Lakes College" => "Northern Lakes College",
            "Northern Lights College" => "Northern Lights College",
            "Northlands College" => "Northlands College",
            "Northwest Community College" => "Northwest Community College",
            "Nova Scotia Community College (NSCC)" => "Nova Scotia Community College (NSCC)",
            "Nunavut Arctic College" => "Nunavut Arctic College",
            "Okanagan College" => "Okanagan College",
            "Olds College" => "Olds College",
            "Parkland College" => "Parkland College",
            "Portage College" => "Portage College",
            "Red Deer College" => "Red Deer College",
            "Red River College of Applied Arts, Science and Technology" => "Red River College of Applied Arts, Science and Technology",
            "Saskatchewan Indian Institute of Technologies (SIIT)" => "Saskatchewan Indian Institute of Technologies (SIIT)",
            "Saskatchewan Polytechnic" => "Saskatchewan Polytechnic",
            "Sault College" => "Sault College",
            "Selkirk College" => "Selkirk College",
            "Seneca College of Applied Arts and Technology" => "Seneca College of Applied Arts and Technology",
            "Southeast College" => "Southeast College",
            "Southern Alberta Institute of Technology (SAIT)" => "Southern Alberta Institute of Technology (SAIT)",
            "St. Clair College" => "St. Clair College",
            "St. Lawrence College" => "St. Lawrence College",
            "TAV College" => "TAV College",
            "Université Sainte-Anne, Collège de l’Acadie" => "Université Sainte-Anne, Collège de l’Acadie",
            "University College of the North" => "University College of the North",
            "University of the Fraser Valley" => "University of the Fraser Valley",
            "Vancouver Community College" => "Vancouver Community College",
            "Vancouver Island University" => "Vancouver Island University",
            "Vanier College" => "Vanier College",
            "Yukon College" => "Yukon College");

            echo elgg_view('input/select', array(
                'name' => $field,
                'id' => $field,
                'class' => "gcconnex-basic-{$field}",
                'value' => $value,
                'options_values' => $colleges, 
            ));       

        } else {

            
            $params = array(
                'name' => $field,
                'id' => $field,
                'class' => 'gcconnex-basic-'.$field,
                'value' => $value,
            );

            // set up label and input field for the basic profile stuff
            echo "<label for='{$field}' class='basic-profile-label {$field}-label'>" . elgg_echo("gcconnex_profile:basic:{$field}")."</label>";
            echo '<div class="basic-profile-field">'; // field wrapper for css styling
			echo elgg_view("input/text", $params);

		} // input field

        echo '</div>'; //close div class = basic-profile-field
        echo '</div>'; //close div class = basic-profile-field-wrapper

    } // end for-loop

    echo '</div>'; // close div class="basic-profile-standard-field-wrapper"
    echo '<div class="basic-profile-social-media-wrapper col-sm-6 col-xs-12">'; // container for css styling, used to group profile content and display them seperately from other fields


	// pre-populate the social media fields and their prepended link for user profiles
    $fields = array(
        'Facebook' => "http://www.facebook.com/",
        'Google Plus' => "http://www.google.com/",
        'GitHub' => "https://github.com/",
        'Twitter' => "https://twitter.com/",
        'Linkedin' => "http://ca.linkedin.com/in/",
        'Pinterest' => "http://www.pinterest.com/",
        'Tumblr' => "https://www.tumblr.com/blog/",
        'Instagram' => "http://instagram.com/",
        'Flickr' => "http://flickr.com/",
        'Youtube' => "http://www.youtube.com/"
    );


    foreach ($fields as $field => $field_link) { // create a label and input box for each social media field on the basic profile

        echo '<div class="basic-profile-field-wrapper social-media-field-wrapper">'; //field wrapper for css styling

        //echo '<div class="basic-profile-label social-media-label ' . $field . '-label">' . $field . ': </div>';
        $field = str_replace(' ', '-', $field); // create a css friendly version of the section name

        $field = strtolower($field);
        if ($field == "google-plus") { $field = "google"; }
        $value = $user->get($field);

        echo '<div class="input-group">'; // input wrapper for prepended link and input box, excludes the input label

        echo '<label for="' . $field . 'Input" class="input-group-addon clearfix">' . $field_link . "</label>"; // prepended link

        // setup the input for this field
        $placeholder = "test";
        if ($field == "facebook") { $placeholder = "User.Name"; }
        if ($field == "google") { $placeholder = "############"; }
        if ($field == "github") { $placeholder = "User"; }
        if ($field == "twitter") { $placeholder = "@user"; }
        if ($field == "linkedin") { $placeholder = "CustomURL"; }
        if ($field == "pinterest") { $placeholder = "Username"; }
        if ($field == "tumblr") { $placeholder = "Username"; }
        if ($field == "instagram") { $placeholder = "@user"; }
        if ($field == "flickr") { $placeholder = "Username"; }
        if ($field == "youtube") { $placeholder = "Username"; }

        $params = array(
            'name' => $field,
            'id' => $field . 'Input',
            'class' => 'editProfileFields gcconnex-basic-field gcconnex-social-media gcconnex-basic-' . $field,
            'placeholder' => $placeholder,
            'value' => $value
        );

        echo elgg_view("input/text", $params); // input field

        echo '</div>'; // close div class="input-group"

        echo '</div>'; // close div class = basic-profile-field-wrapper
    }

    echo '</div>'; // close div class="basic-profile-social-media-wrapper"


    echo '
    </div>
            <div class="panel-footer text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">' . elgg_echo('gcconnex_profile:cancel') . '</button>
                <button type="button" class="btn btn-primary save-profile">' . elgg_echo('gcconnex_profile:basic:save') . '</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->';
}




echo '</div>'; // close div class="gcconnex-profile-name"
//actions dropdown
if (elgg_get_page_owner_guid() != elgg_get_logged_in_user_guid()) {
    $menu = elgg_trigger_plugin_hook('register', "menu:user_hover", array('entity' => $user), array());
    $builder = new ElggMenuBuilder($menu);
    $menu = $builder->getMenu();
    $actions = elgg_extract('action', $menu, array());
    $admin = elgg_extract('admin', $menu, array());

    $profile_actions = '';

	// cyu - GCCON-151 : Add colleague in FR not there (inconsistent FR and EN menu layout) & other issues
    if (elgg_is_logged_in() && $actions) {
		$btn_friend_request = '';
        foreach ($actions as $action) {
			
			if (strcmp($action->getName(),'add_friend') == 0 || strcmp($action->getName(),'remove_friend') == 0) {
				if (!check_entity_relationship(elgg_get_logged_in_user_guid(),'friendrequest',$user->getGUID())) {
					if ($user->isFriend() && strcmp($action->getName(),'remove_friend') == 0) {
						$btn_friend_request = $action->getContent();
						$btn_friend_request_link = $action->getHref();
					}
					if (!$user->isFriend() && strcmp($action->getName(),'add_friend') == 0) {
						$btn_friend_request = $action->getContent(array('class' => 'asdfasdasfad'));
						$btn_friend_request_link = $action->getHref();
					}
				}
			} else {

				if (check_entity_relationship(elgg_get_logged_in_user_guid(),'friendrequest',$user->getGUID()) && strcmp($action->getName(),'friend_request') == 0) {
					$btn_friend_request_link = $action->getHref();
					$btn_friend_request = $action->getContent();
				} else
					$profile_actions .= '<li>' . $action->getContent(array('class' => 'gcconnex-basic-profile-actions')) . '</li>';
			}
        }
    }

    if(elgg_is_logged_in()) {
		echo "<button type='button' class='btn btn-primary' onclick='location.href=\"{$btn_friend_request_link}\"'>{$btn_friend_request}</button>"; // cyu - added button and removed from actions toggle

        echo $add . '<div class="btn-group"><button type="button" class="btn btn-custom mrgn-rght-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ' . elgg_echo('profile:actions') . ' <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu pull-right clearfix">';
        echo $profile_actions;
        echo '</ul></div>';
    }
}

// if admin, display admin links
$admin_links = '';
if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
    $text = elgg_echo('admin:options');

    foreach ($admin as $menu_item) {
        $admin_links .= '<li>' . elgg_view('navigation/menu/elements/item', array('item' => $menu_item)) . '</li>';
    }

    echo '<div class="pull-right btn-group"><button type="button" class="btn btn-custom pull-right dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' .
	$text . '<span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right clearfix">' . $admin_links . '</ul></div>';
}



echo '</div>'; //closes btn-group


echo '<h1 class="pull-left group-title">' . $user->name . '</h1>';
echo '</div>'; // close div class="panel-heading"



echo '<div class="row mrgn-lft-md mrgn-rght-sm">';
echo elgg_view('profile/owner_block');
echo '<div class="col-xs-9 col-md-8 clearfix"><div class="mrgn-lft-md">';

// if user is student or professor, display the correlated information
if (strcmp($user->user_type, 'student') == 0 || strcmp($user->user_type, 'academic') == 0 ) {
    echo '<h3 class="mrgn-tp-0">'.elgg_echo("gcconnex-profile-card:{$user->user_type}", array($user->user_type)).'</h3>';
    echo '<div class="gcconnex-profile-dept">'./*elgg_echo("{$user->institution}", array($user->intstitution))*/$user->institution.'</div>';

// otherwise if user is public servant
} else {
    echo '<h3 class="mrgn-tp-0">' . $user->job . '</h3>';
    echo '<div class="gcconnex-profile-dept">' . $user->department . '</div>';
}

echo '<div class="gcconnex-profile-location">' . $user->location . '</div>';


echo '<div class="gcconnex-profile-contact-info">';

if ($user->phone != null)
    echo '<p class="mrgn-bttm-sm"><i class="fa fa-phone fa-lg"></i> ' . $user->phone . '</p>';

if ($user->mobile != null)
    echo '<p class="mrgn-bttm-sm"><i class="fa fa-mobile fa-lg"></i> ' . $user->mobile . '</p>';

if ($user->email != null)
    echo '<p class="mrgn-bttm-sm"><i class="fa fa-envelope fa-lg"></i> <a href="mailto:' . $user->email . '">' . $user->email . '</a></p>';

if ($user->website != null) {
    echo '<p class="mrgn-bttm-sm"><i class="fa fa-globe fa-lg"></i> ';
    echo elgg_view('output/url', array(
        'href' => $user->website,
        'text' => $user->website
    ));
    echo '</p>';
}

echo '</div></div>'; // close div class="gcconnex-profile-contact-info"



// pre-populate the social media links that we may or may not display depending on whether the user has entered anything for each one..
$social = array('facebook', 'google', 'github', 'twitter', 'linkedin', 'pinterest', 'tumblr', 'instagram', 'flickr', 'youtube');

echo '<div class="gcconnex-profile-social-media-links mrgn-bttm-sm mrgn-lft-md">';
foreach ($social as $media) {
    if ($link = $user->get($media)) {
        if ($media == 'facebook')   { $link = "http://www.facebook.com/" . $link; $class = "fa-facebook";}
        if ($media == 'google')     { $link = "http://plus.google.com/" . $link; $class = "fa-google-plus";}
        if ($media == 'github')     { $link = "https://github.com/" . $link; $class = "fa-github";}
        if ($media == 'twitter')    { $link = "https://twitter.com/" . $link; $class = "fa-twitter";}
        if ($media == 'linkedin')   { $link = "http://ca.linkedin.com/in/" . $link; $class = "fa-linkedin";}
        if ($media == 'pinterest')  { $link = "http://www.pinterest.com/" . $link; $class = "fa-pinterest";}
        if ($media == 'tumblr')     { $link = "https://www.tumblr.com/blog/" . $link; $class = "fa-tumblr";}
        if ($media == 'instagram')  { $link = "http://instagram.com/" . $link; $class = "fa-instagram";}
        if ($media == 'flickr')     { $link = "http://flickr.com/" . $link; $class = "fa-flickr"; }
        if ($media == 'youtube')    { $link = "http://www.youtube.com/" . $link; $class = "fa-youtube";}

        echo '<a href="' . $link . '" target="_blank"><i class="socialMediaIcons fa ' . $class . ' fa-2x"></i></a>';
    }
}
echo '</div>'; // close div class="gcconnex-profile-social-media-links"
echo '</div>';
echo '</div>'; //closes row class




$user = elgg_get_page_owner_entity();

// grab the actions and admin menu items from user hover
$menu = elgg_trigger_plugin_hook('register', "menu:user_hover", array('entity' => $user), array());
$builder = new ElggMenuBuilder($menu);
$menu = $builder->getMenu();
$actions = elgg_extract('action', $menu, array());
$admin = elgg_extract('admin', $menu, array());

$profile_actions = '';
if (elgg_is_logged_in() && $actions) {
    $profile_actions = '<ul class="elgg-menu profile-action-menu mvm">';
    foreach ($actions as $action) {
        $profile_actions .= '<li>' . $action->getContent(array('class' => 'elgg-button elgg-button-action')) . '</li>';
    }
    $profile_actions .= '</ul>';
}

// content links
$content_menu_title = elgg_echo('gcconnex_profile:user_content');
$content_menu = elgg_view_menu('owner_block', array(
    'entity' => elgg_get_page_owner_entity(),
    'class' => 'profile-content-menu',
));

