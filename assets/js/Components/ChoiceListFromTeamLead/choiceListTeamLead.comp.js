import React from "react";
import { connect } from "react-redux";
import {
  setAnswers,
  updateChoiceAnswerTeamLeadSide
} from "../../Actions/action";

class ChoiceListTeamLead extends React.Component {
  onSelect = event => {
    const selectedIndex = event.target.options.selectedIndex;
    const choiceId = event.target.options[selectedIndex].getAttribute(
      "data-value"
    );
    this.props.onSetAnswers(this.props.criteriaId, choiceId);
    this.props.onUpdateChoiceTeamLeadAnswer(this.props.criteriaId, choiceId);
  };

  render() {
    const { choices } = this.props;
    let answer = "Not answered";
    for (let i = 0; i < this.props.choiceList.length; i++) {
      for (let j = 0; j < choices.length; j++) {
        if (this.props.choiceList[i].choiceId === choices[j].id) {
          answer = choices[j].title;
        }
      }
    }

    if (!this.props.managerPage) {
      return <div>{answer}</div>;
    }

    return (
      <select defaultValue={answer} onChange={this.onSelect}>
        {answer === "Not answered" ? (
          <option value="Not answered">--Not answered--</option>
        ) : null}
        {choices.map(choice => (
          <option key={choice.id} value={choice.title} data-value={choice.id}>
            {choice.title}
          </option>
        ))}
      </select>
    );
  }
}

const mapStateToProps = state => ({
    choiceList: state.answerListTeamLeadSide.choiceList,
    managerPage: state.managerPage.selected
});

const mapDispatchToProps = dispatch => ({
  onSetAnswers: (criteriaId, choiceId) =>
    dispatch(setAnswers(criteriaId, choiceId)),
  onUpdateChoiceTeamLeadAnswer: (criteriaId, choiceId) =>
    dispatch(updateChoiceAnswerTeamLeadSide(criteriaId, choiceId))
});

export default connect(mapStateToProps, mapDispatchToProps)(ChoiceListTeamLead);
