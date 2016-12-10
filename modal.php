<?
    $urlTaskDefault = 'https://kmedia.bitrix24.ru/company/personal/user/7/tasks/task/view/';
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавление задания</h4>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#home" role="tab" data-toggle="tab">Основное</a></li>
                    <li><a href="#desc" role="tab" data-toggle="tab">Описание</a></li>
                    <li><a href="#note" role="tab" data-toggle="tab">Комментарии</a></li>
                </ul>
            </div>
            <form role="form" method="post" id="modal_form">
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <div class="form-group">
                                <label for="link">URL задачи</label>
                                <input type="text" class="form-control input-sm" id="link" name="link" value="<?= $urlTaskDefault; ?>">
                            </div>
                            <div class="form-group">
                                <label for="title">Заголовок</label>
                                <input type="text" class="form-control" id="title" name="title" value="">
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-8">
                                    <label for="site">Имя сайта</label>
                                    <input type="text" class="form-control input-sm" id="site" name="site">
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="site_list">...или выбрать</label>
                                    <select class="form-control input-sm" id="site_list">
                                        <option value="">Выберите сайт</option>
                                        <option value="bezvolos.net">bezvolos.net</option>
                                        <option value="hairdesign.ru">hairdesign.ru</option>
										<option value="hairlife.ru">hairlife.ru</option>
										<option value="hoito.ru">hoito.ru</option>
										<option value="krasiviemedia.ru">krasiviemedia.ru</option>
										<option value="kvarc-pesok.ru">kvarc-pesok.ru</option>
                                        <option value="medcentrgoroda.ru">medcentrgoroda.ru</option>
										<option value="netvolos.ru">netvolos.ru</option>
										<option value="russian-hair.org">russian-hair.org</option>
                                        <option value="techradius.com">techradius.com</option>
                                        <option value="triholog.org">triholog.org</option>
										<option value="volosy24.ru">volosy24.ru</option>
										<option value="whitedent.spb.ru">whitedent.spb.ru</option>
										<option value="zagustitelvolos.com">zagustitelvolos.com</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-8">
                                    <label for="status">Статус</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Выберите статус</option>
                                        <option value="В работе">В работе</option>
                                        <option value="Жду ответа" class="waiting">Жду ответа</option>
                                        <option value="Выполнил" class="done">Выполнил</option>
                                        <option value="Отменено" class="canceled">Отменено</option>
                                    </select>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="deadline">Deadline</label>
                                    <input type="date" class="form-control" id="deadline" name="deadline">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="desc">
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <textarea class="form-control input-sm" id="description" rows="20" name="description"></textarea>
                            </div>
                        </div>
                        <div class="tab-pane" id="note">
                            <div class="form-group">
                                <label for="small_note">Короткий комментарий</label>
                                <input class="form-control input-sm" id="small_note" name="small_note">
                            </div>
                            <div class="form-group">
                                <label for="full_note">Полный комментарий</label>
                                <textarea class="form-control input-sm" id="full_note" rows="8" name="full_note"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" id="remove_task" data-dismiss="modal">Удалить</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="save_task" name="save_task" value="save" data-dismiss="modal">Сохранить</button>
                </div>
                <input type="hidden" name="id" value="">
            </form>
        </div> <!-- content -->
    </div>
</div>