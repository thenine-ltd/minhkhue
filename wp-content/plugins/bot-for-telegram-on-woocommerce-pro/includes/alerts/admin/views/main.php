<div id="bftow_bulk_upload" class="milligram">

    <div class="bftow_bulk_upload_new">

        <a href="#" class="milli-button" @click.prevent="selectImage" v-if="!hasImage()">
            <i class="dashicons dashicons-camera-alt"></i>
            <?php esc_html_e('Upload image', 'bftow-pro'); ?>
        </a>

        <a href="#" class="milli-button" @click.prevent="deleteImage" v-if="hasImage()">
            <?php esc_html_e('Delete image', 'bftow-pro'); ?>
        </a>

        <textarea placeholder="<?php esc_attr_e('Enter message', 'bftow-pro'); ?>"
                  v-model="newMessage">

        </textarea>

        <button class="milli-button"
                v-if="hasText() && !loading"
                @click.prevent="createRecord"
                href="#">
            <?php esc_html_e('Send Message', 'bftow-pro'); ?>
        </button>

        <div class="notice notice-warning bftow_notice" v-if="message" v-html="message"></div>

        <div class="bftow_bulk_preview" v-if="hasMessage()">
            <h3><?php esc_html_e('Preview message', 'bftow-pro'); ?></h3>
            <img :src="image.url" v-if="hasImage()"/>
            <span v-html="newMessage" v-if="hasText()"></span>
        </div>

    </div>

    <h5 v-if="records.length">
        <?php esc_html_e('Messages log', 'bftow-pro'); ?>
    </h5>

    <table v-if="records.length">
        <thead>
        <tr>
            <th><?php esc_html_e('ID', 'bftow-pro'); ?></th>
            <th><?php esc_html_e('Message', 'bftow-pro'); ?></th>
            <th><?php esc_html_e('Date', 'bftow-pro'); ?></th>
            <th><?php esc_html_e('Receivers', 'bftow-pro'); ?></th>
            <th style="width: 135px;"><?php esc_html_e('Actions', 'bftow-pro'); ?></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(record, key) in records">
            <td v-html="record.id"></td>
            <td>
                <strong v-html="record.message"></strong>
            </td>
            <td v-html="record.time" class="time-td"></td>
            <td>
                {{record.current_users}}/{{record.total_users}}
            </td>
            <td v-if="continueMessages(record)">
                <a href="#" class="milli-button" @click="sendMessage(key)">
                    <?php esc_html_e('Continue sending', 'bftow-pro'); ?>
                </a>
            </td>
        </tr>
        </tbody>
    </table>

</div>